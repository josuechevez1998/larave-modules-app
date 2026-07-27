#!/usr/bin/env python3
"""Export Stancl Tenancy v3 docs to local markdown files for Cursor skills."""

from __future__ import annotations

import os
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
DOCS_DIR = ROOT / "docs"
SKILL_FILE = ROOT / "SKILL.md"
LOCAL_NAV_FILE = ROOT / "navigation-v3-slugs.txt"
BASE_URL = "https://tenancyforlaravel.com/docs/v3"
NAV_URL = "https://raw.githubusercontent.com/stancl/tenancy-docs/master/navigation.php"
USER_AGENT = "attorney-manager-tenancy-docs-export/1.0"
REQUEST_TIMEOUT = (10, 90)  # connect, read
MAX_RETRIES = 4


def build_session() -> requests.Session:
    session = requests.Session()
    session.headers.update({"User-Agent": USER_AGENT})

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
    if LOCAL_NAV_FILE.exists():
        slugs = [
            line.strip()
            for line in LOCAL_NAV_FILE.read_text(encoding="utf-8").splitlines()
            if line.strip() and not line.strip().startswith("#")
        ]
        if slugs:
            return slugs

    return []


def parse_navigation_php(content: str) -> list[str]:
    v3_start = content.find("'v3' =>")
    if v3_start == -1:
        return []

    v3_block = content[v3_start:]
    next_version = v3_block.find("'v", 10)
    if next_version != -1:
        v3_block = v3_block[:next_version]

    slugs: list[str] = []
    for match in re.finditer(r"=>\s*'([^']+)'", v3_block):
        value = match.group(1)
        if value.startswith("http://") or value.startswith("https://"):
            continue
        if "/" in value or re.fullmatch(r"[a-z0-9-]+(?:/[a-z0-9-]+)*", value):
            slugs.append(value.rstrip("/"))

    return list(dict.fromkeys(slugs))


def fetch_navigation_slugs(session: requests.Session) -> list[str]:
    local_slugs = load_local_slugs()

    if os.environ.get("TENANCY_DOCS_FETCH_NAV") != "1":
        if not local_slugs:
            print("No hay navigation-v3-slugs.txt; intentando GitHub...", file=sys.stderr)
        else:
            print(f"Usando {len(local_slugs)} slugs locales ({LOCAL_NAV_FILE.name})")
            return local_slugs

    try:
        print("Descargando navigation.php desde GitHub...")
        response = session.get(NAV_URL, timeout=REQUEST_TIMEOUT)
        response.raise_for_status()
        remote_slugs = parse_navigation_php(response.text)

        if remote_slugs:
            LOCAL_NAV_FILE.write_text(
                "# Slugs públicos de Tenancy v3\n"
                + "\n".join(remote_slugs)
                + "\n",
                encoding="utf-8",
            )
            print(f"Actualizado {LOCAL_NAV_FILE.name} ({len(remote_slugs)} slugs)")
            return remote_slugs
    except requests.RequestException as exc:
        print(f"GitHub no disponible ({exc}); usando slugs locales.", file=sys.stderr)

    if local_slugs:
        return local_slugs

    raise RuntimeError(
        "No se pudieron obtener slugs. Crea navigation-v3-slugs.txt o revisa tu conexión."
    )


def extract_main_html(soup: BeautifulSoup) -> BeautifulSoup | None:
    selectors = [
        "article",
        "main",
        "[role='main']",
        ".prose",
        ".markdown",
        ".documentation",
    ]

    for selector in selectors:
        node = soup.select_one(selector)
        if node and node.get_text(strip=True):
            return node

    return soup.body


def clean_markdown(markdown: str) -> str:
    markdown = re.sub(r"\n{3,}", "\n\n", markdown)
    markdown = markdown.replace("Subscribe\n\nTwitter\n\nGitHub", "")
    markdown = markdown.replace("Made by ArchTech.", "")
    return markdown.strip() + "\n"


def slug_to_title(slug: str) -> str:
    name = slug.split("/")[-1].replace("-", " ")
    return name[:1].upper() + name[1:]


def fetch_page(slug: str, session: requests.Session) -> tuple[str, str]:
    url = f"{BASE_URL}/{slug}".rstrip("/")

    last_error: Exception | None = None
    for attempt in range(1, MAX_RETRIES + 1):
        try:
            response = session.get(url, timeout=REQUEST_TIMEOUT)
            response.raise_for_status()
            break
        except requests.RequestException as exc:
            last_error = exc
            if attempt == MAX_RETRIES:
                raise
            wait = attempt * 2
            print(f"  retry {attempt}/{MAX_RETRIES} {slug} ({exc})", file=sys.stderr)
            time.sleep(wait)
    else:
        raise last_error or RuntimeError(f"No se pudo descargar {url}")

    soup = BeautifulSoup(response.text, "html.parser")

    for tag in soup.select("nav, footer, header, script, style, form"):
        tag.decompose()

    title = soup.title.get_text(strip=True) if soup.title else slug_to_title(slug)
    title = re.sub(r"\s*\|\s*Tenancy for Laravel\s*$", "", title)

    content = extract_main_html(soup)
    if content is None:
        raise RuntimeError(f"Could not extract content for {url}")

    markdown = clean_markdown(html_to_md(str(content), heading_style="ATX", bullets="-"))

    header = textwrap.dedent(
        f"""\
        # {title}

        > Fuente: {url}
        > Exportado: {datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")}

        """
    )

    return title, header + markdown


def write_skill_index(slugs: list[str], exported: list[tuple[str, str, str]]) -> None:
    exported_at = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")
    lines = [
        "---",
        "name: tenancy-v3-docs",
        "description: >-",
        "  Documentación local de Stancl Tenancy v3 exportada desde tenancyforlaravel.com.",
        "  Usar cuando trabajes con stancl/tenancy, multi-tenancy, tenants, domains,",
        "  identificación de tenants, colas, migraciones tenant o bootstrappers.",
        "---",
        "",
        "# Stancl Tenancy v3 — documentación local",
        "",
        f"Exportada el **{exported_at}** desde [{BASE_URL}]({BASE_URL}).",
        "",
        "## Cuándo usar este skill",
        "",
        "- Configurar o depurar `stancl/tenancy` v3 en Laravel.",
        "- Crear tenants, dominios, pipelines `TenantCreated`, colas o migraciones tenant.",
        "- Comparar modos automatic/manual, single-db vs multi-db.",
        "- Integraciones (Livewire, Sanctum, Sail, Spatie, etc.).",
        "",
        "## Cómo actualizar",
        "",
        "Desde la raíz del skill:",
        "",
        "```bash",
        "bash run.sh",
        "```",
        "",
        "Opcional: actualizar slugs desde GitHub (si tu red lo permite):",
        "",
        "```bash",
        "TENANCY_DOCS_FETCH_NAV=1 bash run.sh",
        "```",
        "",
        "## Índice de páginas",
        "",
    ]

    for slug, title, _ in exported:
        lines.append(f"- [{title}](docs/{slug.replace('/', '-')}.md) — `{slug}`")

    lines.extend(["", "## Páginas no exportadas", ""])

    exported_slugs = {item[0] for item in exported}
    missing = [slug for slug in slugs if slug not in exported_slugs]
    if missing:
        for slug in missing:
            lines.append(f"- `{slug}` (no disponible o requiere acceso sponsor)")
    else:
        lines.append("- Ninguna.")

    SKILL_FILE.write_text("\n".join(lines) + "\n", encoding="utf-8")


def main() -> int:
    DOCS_DIR.mkdir(parents=True, exist_ok=True)

    session = build_session()
    slugs = fetch_navigation_slugs(session)

    exported: list[tuple[str, str, str]] = []
    failures: list[str] = []

    print(f"Exportando {len(slugs)} páginas de Tenancy v3...")

    for slug in slugs:
        outfile = DOCS_DIR / f"{slug.replace('/', '-')}.md"
        try:
            title, markdown = fetch_page(slug, session)
            outfile.write_text(markdown, encoding="utf-8")
            exported.append((slug, title, str(outfile.relative_to(ROOT))))
            print(f"  OK  {slug}")
        except Exception as exc:  # noqa: BLE001 - report and continue
            failures.append(f"{slug}: {exc}")
            print(f"  ERR {slug} -> {exc}", file=sys.stderr)

    write_skill_index(slugs, exported)

    print("")
    print(f"Exportadas: {len(exported)}")
    print(f"Fallidas:   {len(failures)}")
    print(f"Skill:      {SKILL_FILE}")
    print(f"Docs:       {DOCS_DIR}")

    if not exported:
        return 1

    return 0 if not failures else 0


if __name__ == "__main__":
    raise SystemExit(main())
