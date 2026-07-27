#!/usr/bin/env python3
"""Export manual de docs laravelmodules.com v13 → .cursor/skills/laravel-modules-13/*.mdc"""

from __future__ import annotations

import re
import sys
import textwrap
import time
from datetime import datetime, timezone
from pathlib import Path
from urllib.parse import urlparse

import requests
from bs4 import BeautifulSoup
from markdownify import markdownify as html_to_md
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

ROOT = Path(__file__).resolve().parents[2]
OUT = ROOT / ".cursor" / "skills" / "laravel-modules-13"
PREFIX = "laravel-modules-13"
PAUSE = 0.2
UA = "Mozilla/5.0 (compatible; cursor-docs-export-manual/1.0)"

# Índice oficial v13 (nav del sitio)
PAGES = [
    # Getting Started
    "https://laravelmodules.com/docs/13/getting-started/introduction",
    "https://laravelmodules.com/docs/13/getting-started/requirements",
    "https://laravelmodules.com/docs/13/getting-started/changelog",
    "https://laravelmodules.com/docs/13/getting-started/upgrade",
    "https://laravelmodules.com/docs/13/getting-started/installation-and-setup",
    "https://laravelmodules.com/docs/13/getting-started/lumen",
    "https://laravelmodules.com/docs/13/getting-started/questions-and-issues",
    # Basic Usage
    "https://laravelmodules.com/docs/13/basic-usage/configuration",
    "https://laravelmodules.com/docs/13/basic-usage/compiling-assets",
    "https://laravelmodules.com/docs/13/basic-usage/creating-a-module",
    "https://laravelmodules.com/docs/13/basic-usage/custom-namespaces",
    "https://laravelmodules.com/docs/13/basic-usage/helpers",
    "https://laravelmodules.com/docs/13/basic-usage/routing",
    "https://laravelmodules.com/docs/13/basic-usage/controllers",
    "https://laravelmodules.com/docs/13/basic-usage/views",
    "https://laravelmodules.com/docs/13/basic-usage/service-providers",
    "https://laravelmodules.com/docs/13/basic-usage/middleware",
    # Advanced
    "https://laravelmodules.com/docs/13/advanced/artisan-commands",
    "https://laravelmodules.com/docs/13/advanced/facade-methods",
    "https://laravelmodules.com/docs/13/advanced/module-console-commands",
    "https://laravelmodules.com/docs/13/advanced/module-methods",
    "https://laravelmodules.com/docs/13/advanced/module-resources",
    "https://laravelmodules.com/docs/13/advanced/languages",
    "https://laravelmodules.com/docs/13/advanced/tests",
    "https://laravelmodules.com/docs/13/advanced/publishing-modules",
    "https://laravelmodules.com/docs/13/advanced/registering-module-events",
    # Database
    "https://laravelmodules.com/docs/13/database/migrations",
    "https://laravelmodules.com/docs/13/database/models",
    "https://laravelmodules.com/docs/13/database/seeders-and-factories",
    # Resources (sección "Resources and Packages" en el nav)
    "https://laravelmodules.com/docs/13/resources/resources",
    "https://laravelmodules.com/docs/13/resources/inertia",
    "https://laravelmodules.com/docs/13/resources/custom-module-generator",
    "https://laravelmodules.com/docs/13/resources/livewire",
    "https://laravelmodules.com/docs/13/resources/spatie-laravel-permission",
    "https://laravelmodules.com/docs/13/resources/laravel-module-generator",
]

# Alias por si el slug del sitio difiere
FALLBACKS = {
    "https://laravelmodules.com/docs/13/getting-started/installation-and-setup": [
        "https://laravelmodules.com/docs/13/getting-started/installation",
    ],
    "https://laravelmodules.com/docs/13/advanced/module-console-commands": [
        "https://laravelmodules.com/docs/13/advanced/console-commands",
    ],
    "https://laravelmodules.com/docs/13/advanced/languages": [
        "https://laravelmodules.com/docs/13/advanced/languages-and-translations",
    ],
    "https://laravelmodules.com/docs/13/resources/laravel-module-generator": [
        "https://laravelmodules.com/docs/13/resources/module-generator",
        "https://laravelmodules.com/docs/13/resources-and-packages/module-generator",
    ],
    "https://laravelmodules.com/docs/13/resources/resources": [
        "https://laravelmodules.com/docs/13/resources-and-packages/resources",
    ],
    "https://laravelmodules.com/docs/13/resources/inertia": [
        "https://laravelmodules.com/docs/13/resources-and-packages/inertia",
    ],
    "https://laravelmodules.com/docs/13/resources/custom-module-generator": [
        "https://laravelmodules.com/docs/13/resources-and-packages/custom-module-generator",
    ],
    "https://laravelmodules.com/docs/13/resources/livewire": [
        "https://laravelmodules.com/docs/13/resources-and-packages/livewire",
    ],
    "https://laravelmodules.com/docs/13/resources/spatie-laravel-permission": [
        "https://laravelmodules.com/docs/13/resources-and-packages/spatie-laravel-permission",
        "https://laravelmodules.com/docs/13/resources-and-packages/spaties-laravel-permission",
    ],
}


def session() -> requests.Session:
    s = requests.Session()
    s.headers.update({"User-Agent": UA, "Accept": "text/html"})
    retry = Retry(total=4, backoff_factor=1.2, status_forcelist=(429, 500, 502, 503, 504))
    s.mount("https://", HTTPAdapter(max_retries=retry))
    return s


def slug_from_url(url: str) -> str:
    path = urlparse(url).path.strip("/")
    # docs/13/getting-started/introduction → getting-started-introduction
    parts = path.split("/")
    if len(parts) >= 3 and parts[0] == "docs":
        return "-".join(parts[2:])
    return path.replace("/", "-")


def clean_md(md: str) -> str:
    md = re.sub(r"(?im)^copied!\s*$", "", md)
    md = re.sub(r"(?im)^on this page\s*$", "", md)
    md = re.sub(r"(?im)^sponsors\s*$", "", md)
    md = re.sub(r"(?im)^edit this page on github\s*$", "", md)
    md = re.sub(r"(?im)^next page:.*$", "", md)
    md = re.sub(r"\n{3,}", "\n\n", md)
    # Quitar bloques de código duplicados típicos del sitio (Copied! + doble paste)
    return md.strip() + "\n"


def extract(html: str, url: str) -> tuple[str, str]:
    soup = BeautifulSoup(html, "html.parser")
    title = soup.title.get_text(strip=True) if soup.title else url
    title = re.sub(r"\s*[-|·]\s*.*$", "", title).strip() or title

    main = None
    for sel in ("article", "main", "[role='main']", ".prose", "#content"):
        node = soup.select_one(sel)
        if node and len(node.get_text(strip=True)) > 80:
            main = node
            break
    if main is None:
        main = soup.body

    if main is None:
        raise RuntimeError("sin contenido")

    for tag in main.select("nav, footer, header, script, style, aside, .sidebar"):
        tag.decompose()

    h1 = main.select_one("h1")
    if h1:
        title = h1.get_text(" ", strip=True)

    md = clean_md(html_to_md(str(main), heading_style="ATX", bullets="-"))
    if len(md.strip()) < 40:
        raise RuntimeError("contenido corto")

    header = textwrap.dedent(
        f"""\
        # {title}

        > Fuente: {url}
        > Exportado: {datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")}

        """
    )
    return title, header + md


def fetch(s: requests.Session, url: str) -> tuple[str, str]:
    candidates = [url] + FALLBACKS.get(url, [])
    last_err: Exception | None = None
    for candidate in candidates:
        try:
            r = s.get(candidate, timeout=(15, 90), allow_redirects=True)
            if r.status_code == 404:
                last_err = RuntimeError(f"404 {candidate}")
                continue
            r.raise_for_status()
            return str(r.url), r.text
        except Exception as exc:  # noqa: BLE001
            last_err = exc
    raise last_err or RuntimeError(url)


def frontmatter(title: str, path: str) -> str:
    desc = (
        f"Documentación local ({PREFIX}): {title} ({path}). "
        "Referencia nwidart/laravel-modules v13."
    )
    return f"---\nalwaysApply: false\ndescription: {desc}\n---\n\n"


def write_index(exported: list[tuple[str, str, str]], missing: list[str]) -> None:
    lines = [
        "---",
        "alwaysApply: false",
        f"description: Índice docs locales nwidart/laravel-modules v13 ({PREFIX}).",
        "---",
        "",
        f"# {PREFIX} — documentación local",
        "",
        f"Exportada el **{datetime.now(timezone.utc).strftime('%Y-%m-%d %H:%M UTC')}** "
        "desde [laravelmodules.com/docs/13](https://laravelmodules.com/docs/13/getting-started/introduction).",
        "",
        f"Total exportado: **{len(exported)}** páginas.",
        "",
        "## Índice",
        "",
    ]
    for _url, title, name in exported:
        lines.append(f"- [{title}]({name})")
    lines.extend(["", "## No exportadas", ""])
    if missing:
        lines.extend(f"- {m}" for m in missing)
    else:
        lines.append("- Ninguna.")
    (OUT / f"{PREFIX}-docs.mdc").write_text("\n".join(lines) + "\n", encoding="utf-8")


def main() -> int:
    OUT.mkdir(parents=True, exist_ok=True)
    s = session()
    exported: list[tuple[str, str, str]] = []
    missing: list[str] = []

    print(f"Salida: {OUT}")
    print(f"Páginas: {len(PAGES)}")

    for i, url in enumerate(PAGES, 1):
        slug = slug_from_url(url)
        filename = f"{PREFIX}-{slug}.mdc"
        try:
            final_url, html = fetch(s, url)
            title, body = extract(html, final_url)
            path = (OUT / filename)
            path.write_text(frontmatter(title, urlparse(final_url).path) + body, encoding="utf-8")
            exported.append((final_url, title, filename))
            print(f"OK  [{i}/{len(PAGES)}] {final_url} -> {filename}")
        except Exception as exc:  # noqa: BLE001
            missing.append(f"`{url}` — {exc}")
            print(f"ERR [{i}/{len(PAGES)}] {url} -> {exc}", file=sys.stderr)
        time.sleep(PAUSE)

    write_index(exported, missing)
    print(f"\nExportadas: {len(exported)}")
    print(f"Fallidas:   {len(missing)}")
    print(f"Índice:     {OUT / f'{PREFIX}-docs.mdc'}")
    return 0 if exported else 1


if __name__ == "__main__":
    raise SystemExit(main())
