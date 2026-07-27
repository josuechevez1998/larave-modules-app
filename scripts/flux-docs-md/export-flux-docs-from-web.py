#!/usr/bin/env python3
"""Export Flux UI docs to local markdown/.mdc files for Cursor skills."""

from __future__ import annotations

import re
import sys
import textwrap
import time
from datetime import datetime, timezone
from pathlib import Path

import requests
from bs4 import BeautifulSoup
from markdownify import markdownify as html_to_md
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

ROOT = Path(__file__).resolve().parent
PROJECT_ROOT = ROOT.parent.parent
DOCS_DIR = ROOT / "docs"
SKILLS_DIR = PROJECT_ROOT / ".cursor" / "skills" / "flux"
SKILL_FILE = ROOT / "SKILL.md"
LOCAL_NAV_FILE = ROOT / "navigation-flux-slugs.txt"
SITE_URL = "https://fluxui.dev"
USER_AGENT = (
    "Mozilla/5.0 (compatible; consule-manager-flux-docs-export/1.0; +local-dev)"
)
REQUEST_TIMEOUT = (15, 120)
MAX_RETRIES = 5
REQUEST_PAUSE_SECONDS = 0.35


def build_session() -> requests.Session:
    session = requests.Session()
    session.headers.update(
        {
            "User-Agent": USER_AGENT,
            "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language": "en-US,en;q=0.9",
        }
    )

    retry = Retry(
        total=MAX_RETRIES,
        connect=MAX_RETRIES,
        read=MAX_RETRIES,
        backoff_factor=1.5,
        status_forcelist=(429, 500, 502, 503, 504),
        allowed_methods=("GET",),
    )
    adapter = HTTPAdapter(max_retries=retry)
    session.mount("https://", adapter)
    session.mount("http://", adapter)

    return session


def load_local_slugs() -> list[str]:
    if not LOCAL_NAV_FILE.exists():
        return []

    return [
        line.strip().lstrip("/")
        for line in LOCAL_NAV_FILE.read_text(encoding="utf-8").splitlines()
        if line.strip() and not line.strip().startswith("#")
    ]


def extract_main_html(soup: BeautifulSoup) -> BeautifulSoup | None:
    selectors = [
        "[data-flux-main]",
        '[class*="[grid-area:main]"]',
        "article",
        "main",
        "[role='main']",
        ".prose",
        ".markdown",
        ".documentation",
        "#content",
    ]

    for selector in selectors:
        node = soup.select_one(selector)
        if node and node.get_text(strip=True):
            return node

    return soup.body


def clean_markdown(markdown: str) -> str:
    markdown = re.sub(r"(?im)^copy to clipboard\s*$", "", markdown)
    markdown = re.sub(r"(?im)^toggle sidebar\s*$", "", markdown)
    markdown = re.sub(r"(?im)^toggle dark mode.*$", "", markdown)
    markdown = re.sub(r"(?im)^copyright ©.*$", "", markdown)
    markdown = re.sub(r"(?im)^built with\s*$", "", markdown)
    markdown = re.sub(r"(?im)^by\s*$", "", markdown)
    markdown = re.sub(r"(?im)^caleb porzio and hugo sainte-marie\s*$", "", markdown)
    markdown = re.sub(r"(?im)^terms of service\s*$", "", markdown)
    markdown = re.sub(r"(?im)^on this page\s*$", "", markdown)
    markdown = re.sub(r"(?im)^new\s*$", "", markdown)
    markdown = re.sub(r"\n{3,}", "\n\n", markdown)
    return markdown.strip() + "\n"


def path_to_title(path: str) -> str:
    name = path.split("/")[-1].replace("-", " ")
    return name[:1].upper() + name[1:]


def path_to_filename(path: str) -> str:
    # docs/installation -> flux-installation
    # components/button -> flux-button
    # layouts/header -> flux-layouts-header
    parts = path.split("/")
    if len(parts) == 2 and parts[0] == "docs":
        return f"flux-{parts[1]}"
    if len(parts) == 2 and parts[0] == "components":
        return f"flux-{parts[1]}"
    return "flux-" + path.replace("/", "-")


def looks_like_wrong_page(path: str, final_url: str, markdown: str) -> bool:
    expected_leaf = path.split("/")[-1]
    if expected_leaf in final_url.rstrip("/").split("/")[-1]:
        # URL matches; still check body for soft redirects to installation
        if path != "docs/installation":
            heading_match = re.search(r"^#\s+(.+)$", markdown, re.MULTILINE)
            if heading_match and heading_match.group(1).strip().lower() == "installation":
                # Some pages may mention installation; only fail if title heading is Installation
                # and final URL is installation
                if "installation" in final_url and expected_leaf != "installation":
                    return True
        return False

    if "installation" in final_url and path != "docs/installation":
        return True

    return False


def fetch_page(path: str, session: requests.Session) -> tuple[str, str]:
    url = f"{SITE_URL}/{path}".rstrip("/")

    last_error: Exception | None = None
    response = None
    for attempt in range(1, MAX_RETRIES + 1):
        try:
            response = session.get(url, timeout=REQUEST_TIMEOUT, allow_redirects=True)
            if response.status_code == 403:
                wait = attempt * 3
                print(f"  retry {attempt}/{MAX_RETRIES} {path} (403)", file=sys.stderr)
                time.sleep(wait)
                continue
            response.raise_for_status()
            break
        except requests.RequestException as exc:
            last_error = exc
            if attempt == MAX_RETRIES:
                raise
            wait = attempt * 2
            print(f"  retry {attempt}/{MAX_RETRIES} {path} ({exc})", file=sys.stderr)
            time.sleep(wait)
    else:
        raise last_error or RuntimeError(f"No se pudo descargar {url}")

    assert response is not None

    soup = BeautifulSoup(response.text, "html.parser")

    title = soup.title.get_text(strip=True) if soup.title else path_to_title(path)
    title = re.sub(r"\s*[·|]\s*Flux\s*$", "", title).strip()

    content = extract_main_html(soup)
    if content is None:
        raise RuntimeError(f"Could not extract content for {url}")

    # Limpiar solo dentro del contenido principal (no destruir selectores previos)
    for tag in content.select(
        "nav, footer, header, script, style, form, aside, [data-docs-toc]"
    ):
        tag.decompose()

    markdown = clean_markdown(html_to_md(str(content), heading_style="ATX", bullets="-"))

    if looks_like_wrong_page(path, str(response.url), markdown):
        raise RuntimeError(
            f"La página {url} redirigió a {response.url} (contenido incorrecto)"
        )

    if len(markdown.strip()) < 80:
        raise RuntimeError(f"Contenido demasiado corto para {url}")

    header = textwrap.dedent(
        f"""\
        # {title}

        > Fuente: {response.url}
        > Exportado: {datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")}

        """
    )

    return title, header + markdown


def mdc_frontmatter(path: str, title: str) -> str:
    description = (
        f"Documentación local de Flux UI: {title} ({path}). "
        "Usar al construir o depurar UI con Livewire Flux (flux:*), "
        "componentes, layouts, theming o instalación de Flux."
    )
    return (
        "---\n"
        "alwaysApply: false\n"
        f"description: {description}\n"
        "---\n\n"
    )


def write_skill_index(paths: list[str], exported: list[tuple[str, str]]) -> None:
    exported_at = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")
    lines = [
        "---",
        "name: flux-ui-docs",
        "description: >-",
        "  Documentación local de Flux UI (Livewire) exportada desde fluxui.dev.",
        "  Usar cuando trabajes con componentes flux:*, instalación, theming,",
        "  dark mode, layouts header/sidebar o personalización de Flux.",
        "---",
        "",
        "# Flux UI — documentación local",
        "",
        f"Exportada el **{exported_at}** desde [{SITE_URL}]({SITE_URL}).",
        "",
        "## Cuándo usar este skill",
        "",
        "- Instalar o actualizar `livewire/flux` / Flux Pro.",
        "- Usar componentes `<flux:*>` en Blade/Livewire.",
        "- Theming, dark mode, layouts y personalización.",
        "",
        "## Cómo actualizar",
        "",
        "Desde la raíz del exportador:",
        "",
        "```bash",
        "bash run.sh",
        "```",
        "",
        "Los `.mdc` se escriben en `.cursor/skills/flux/`.",
        "",
        "## Índice de páginas",
        "",
    ]

    for path, title in exported:
        filename = path_to_filename(path)
        lines.append(f"- [{title}]({filename}.mdc) — `{path}`")

    lines.extend(["", "## Páginas no exportadas", ""])

    exported_paths = {item[0] for item in exported}
    missing = [path for path in paths if path not in exported_paths]
    if missing:
        for path in missing:
            lines.append(f"- `{path}` (no disponible, redirect o error)")
    else:
        lines.append("- Ninguna.")

    SKILL_FILE.write_text("\n".join(lines) + "\n", encoding="utf-8")

    index_mdc = SKILLS_DIR / "flux-docs.mdc"
    index_body = "\n".join(lines[7:])
    index_mdc.write_text(
        "---\n"
        "alwaysApply: false\n"
        "description: Índice de documentación local Flux UI. Usar al buscar componentes flux:* o guías de Flux.\n"
        "---\n\n"
        + index_body
        + "\n",
        encoding="utf-8",
    )


def main() -> int:
    DOCS_DIR.mkdir(parents=True, exist_ok=True)
    SKILLS_DIR.mkdir(parents=True, exist_ok=True)

    session = build_session()
    paths = load_local_slugs()
    if not paths:
        raise RuntimeError(
            f"No hay rutas en {LOCAL_NAV_FILE}. Crea el archivo con la lista de páginas."
        )

    exported: list[tuple[str, str]] = []
    failures: list[str] = []

    print(f"Exportando {len(paths)} páginas de Flux UI...")
    print(f"Skills destino: {SKILLS_DIR}")

    for path in paths:
        filename = path_to_filename(path)
        md_out = DOCS_DIR / f"{filename}.md"
        mdc_out = SKILLS_DIR / f"{filename}.mdc"

        try:
            title, markdown = fetch_page(path, session)
            md_out.write_text(markdown, encoding="utf-8")
            mdc_out.write_text(mdc_frontmatter(path, title) + markdown, encoding="utf-8")
            exported.append((path, title))
            print(f"  OK  {path} -> {mdc_out.name}")
        except Exception as exc:  # noqa: BLE001 - report and continue
            failures.append(f"{path}: {exc}")
            print(f"  ERR {path} -> {exc}", file=sys.stderr)

        time.sleep(REQUEST_PAUSE_SECONDS)

    write_skill_index(paths, exported)

    print("")
    print(f"Exportadas: {len(exported)}")
    print(f"Fallidas:   {len(failures)}")
    if failures:
        for item in failures:
            print(f"  - {item}", file=sys.stderr)
    print(f"Skill:      {SKILL_FILE}")
    print(f"Docs:       {DOCS_DIR}")
    print(f"Skills:     {SKILLS_DIR}/")

    if not exported:
        return 1

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
