#!/usr/bin/env python3
"""
Exportador genérico de documentación web → Cursor skills (.mdc).

Escribe por carpeta según el prefijo:
  .cursor/skills/{prefix}/{prefix}-*.mdc

Uso:
  python export_docs.py https://livewire.laravel.com/docs/4.x/quickstart
  python export_docs.py https://fluxui.dev/docs/installation --prefix=flux
  python export_docs.py https://www.chartjs.org/docs/latest/ --prefix=chartjs
  python export_docs.py URL --dry-run --limit=5
  python export_docs.py URL --flat   # salida plana (legacy)
"""

from __future__ import annotations

import argparse
import re
import sys
import textwrap
import time
from collections import deque
from datetime import datetime, timezone
from pathlib import Path
from urllib.parse import urljoin, urlparse, urlunparse

import requests
from bs4 import BeautifulSoup
from markdownify import markdownify as html_to_md
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

ROOT = Path(__file__).resolve().parent
DEFAULT_OUTPUT = ROOT.parent.parent / ".cursor" / "skills"
USER_AGENT = "Mozilla/5.0 (compatible; cursor-docs-export/1.0; +local-dev)"
REQUEST_TIMEOUT = (15, 120)
MAX_RETRIES = 5
REQUEST_PAUSE_SECONDS = 0.35
MIN_MARKDOWN_CHARS = 40

CONTENT_SELECTORS = [
    "[data-flux-main]",
    '[class*="[grid-area:main]"]',
    "article.prose",
    ".prose",
    ".theme-default-content",
    ".content__default",
    "main.page",
    "article",
    "main",
    "[role='main']",
    ".markdown",
    ".documentation",
    "#content",
]

SKIP_PATH_FRAGMENTS = (
    "/login",
    "/register",
    "/pricing",
    "/blog",
    "/dashboard",
    "/account",
    "/cart",
    "/checkout",
)

# Segmentos de “versión” en /docs/{segment}/ (además de 4.x / v3)
DOCS_VERSION_ALIASES = frozenset(
    {
        "latest",
        "next",
        "stable",
        "current",
        "master",
        "main",
        "dev",
        "canary",
    }
)

ASSET_EXTENSIONS = (
    ".png",
    ".jpg",
    ".jpeg",
    ".gif",
    ".svg",
    ".webp",
    ".ico",
    ".pdf",
    ".zip",
    ".css",
    ".js",
    ".map",
    ".woff",
    ".woff2",
    ".ttf",
    ".eot",
)


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


def normalize_url(url: str) -> str:
    parsed = urlparse(url.strip())
    if not parsed.scheme or not parsed.netloc:
        raise ValueError(f"URL inválida: {url}")
    path = parsed.path or "/"
    # Conservar .html; solo normalizar trailing slash en directorios
    if path.endswith(".html") or path.endswith(".htm"):
        path = path.rstrip("/")
    else:
        path = path.rstrip("/") or "/"
    return urlunparse((parsed.scheme, parsed.netloc, path, "", "", ""))


def canonical_page_key(url: str) -> str:
    """Clave para deduplicar /foo, /foo/ e /foo/index.html."""
    parsed = urlparse(normalize_url(url))
    path = parsed.path.rstrip("/") or "/"
    if path.endswith("/index.html"):
        path = path[: -len("/index.html")] or "/"
    elif path.endswith("index.html"):
        path = path[: -len("index.html")].rstrip("/") or "/"
    return urlunparse((parsed.scheme, parsed.netloc, path, "", "", ""))


def infer_prefix(start_url: str) -> str:
    parsed = urlparse(start_url)
    host = (parsed.hostname or "").removeprefix("www.")
    path = parsed.path.strip("/")

    version = None
    for part in path.split("/"):
        if re.fullmatch(r"\d+\.x", part):
            version = part.replace(".", "")
            break
        if re.fullmatch(r"v\d+", part):
            version = part
            break

    host_map = {
        "livewire.laravel.com": "livewire",
        "fluxui.dev": "flux",
        "v1.fluxui.dev": "flux-v1",
        "tenancyforlaravel.com": "tenancy",
        "laravel.com": "laravel",
        "chartjs.org": "chartjs",
        "www.chartjs.org": "chartjs",
    }

    base = host_map.get(host)
    if not base:
        labels = host.split(".")
        base = labels[-2] if len(labels) >= 2 else labels[0]
        base = re.sub(r"[^a-z0-9]+", "-", base.lower()).strip("-") or "docs"

    if version:
        return f"{base}-{version}"
    return base


def is_docs_version_segment(segment: str) -> bool:
    if segment in DOCS_VERSION_ALIASES:
        return True
    if re.fullmatch(r"\d+\.x", segment):
        return True
    if re.fullmatch(r"v\d+", segment):
        return True
    return False


def docs_base_path(start_url: str) -> str:
    """
    /docs/4.x/quickstart → /docs/4.x/
    /docs/latest/getting-started → /docs/latest/
    /docs/installation → /docs/
    /components/button → /components/
    """
    path = urlparse(start_url).path.rstrip("/")
    parts = [p for p in path.split("/") if p]
    if not parts:
        return "/"
    if len(parts) == 1:
        return f"/{parts[0]}/"
    if parts[0] in {"docs", "documentation"} and len(parts) >= 2:
        if is_docs_version_segment(parts[1]):
            return f"/{parts[0]}/{parts[1]}/"
        return f"/{parts[0]}/"
    return f"/{parts[0]}/"


def path_to_slug(path: str, base_path: str) -> str:
    relative = path
    base_root = base_path.rstrip("/")
    if path.startswith(base_root):
        relative = path[len(base_root) :].lstrip("/")
    relative = relative.strip("/") or "index"
    if relative.endswith(".html"):
        relative = relative[: -len(".html")]
    return relative.replace("/", "-")


def filename_for(prefix: str, path: str, base_path: str, start_host: str, page_url: str) -> str:
    parsed = urlparse(page_url)
    page_path = parsed.path.rstrip("/") or "/"

    if not page_path.startswith(base_path.rstrip("/")):
        slug = page_path.strip("/").replace("/", "-") or "index"
        if slug.endswith(".html"):
            slug = slug[: -len(".html")]
        return f"{prefix}-{slug}"

    slug = path_to_slug(page_path, base_path)
    return f"{prefix}-{slug}"


def clean_markdown(markdown: str) -> str:
    patterns = [
        r"(?im)^copy to clipboard\s*$",
        r"(?im)^toggle sidebar\s*$",
        r"(?im)^toggle dark mode.*$",
        r"(?im)^on this page\s*$",
        r"(?im)^new\s*$",
        r"(?im)^pro ui components\s*$",
        r"(?im)^continue to .*$",
        r"(?im)^last updated:.*$",
        r"(?im)^edit this page.*$",
    ]
    for pattern in patterns:
        markdown = re.sub(pattern, "", markdown)
    markdown = re.sub(r"\n{3,}", "\n\n", markdown)
    return markdown.strip() + "\n"


def extract_main_html(soup: BeautifulSoup) -> BeautifulSoup | None:
    for selector in CONTENT_SELECTORS:
        node = soup.select_one(selector)
        if node and len(node.get_text(strip=True)) > 40:
            return node
    return soup.body


def should_keep_link(href: str, start_url: str, base_path: str, scope: str) -> bool:
    if not href or href.startswith(("#", "mailto:", "tel:", "javascript:")):
        return False

    absolute = urljoin(start_url, href)
    parsed = urlparse(absolute)
    start = urlparse(start_url)

    if parsed.netloc != start.netloc:
        return False
    if parsed.query:
        return False

    path = parsed.path or "/"
    lower = path.lower()
    if any(frag in lower for frag in SKIP_PATH_FRAGMENTS):
        return False
    if any(lower.endswith(ext) for ext in ASSET_EXTENSIONS):
        return False
    if "/assets/" in lower:
        return False

    base_root = base_path.rstrip("/")

    # Si la URL base es versionada (/docs/4.x/ o /docs/latest/), no mezclar otras versiones.
    versioned = bool(
        re.search(
            r"/(?:docs|documentation)/(?:\d+\.x|v\d+|latest|next|stable|current|master|main|dev|canary)/?$",
            base_root + "/",
        )
    )
    if versioned:
        return path.rstrip("/") == base_root or path.startswith(base_root + "/")

    if scope == "base":
        return path.rstrip("/") == base_root or path.startswith(base_root + "/")

    first = path.strip("/").split("/")[0] if path.strip("/") else ""
    return first in {
        "docs",
        "documentation",
        "components",
        "layouts",
        "guide",
        "guides",
        "reference",
        "api",
        "samples",
    } or path.startswith(base_root + "/") or path.rstrip("/") == base_root


def extract_links_from_html(start_url: str, html: str, base_path: str, scope: str) -> list[str]:
    soup = BeautifulSoup(html, "html.parser")
    found: list[str] = []
    seen: set[str] = set()

    for anchor in soup.select("a[href]"):
        href = anchor.get("href") or ""
        if not should_keep_link(href, start_url, base_path, scope):
            continue
        absolute = normalize_url(urljoin(start_url, href))
        key = canonical_page_key(absolute)
        if key in seen:
            continue
        seen.add(key)
        found.append(absolute)

    return found


def discover_spa_bundle_urls(start_url: str, html: str) -> list[str]:
    """Localiza bundles app.*.js / index-*.js que suelen contener el router/sidebar."""
    soup = BeautifulSoup(html, "html.parser")
    urls: list[str] = []
    seen: set[str] = set()

    candidates: list[str] = []
    for tag in soup.select("script[src], link[href]"):
        src = tag.get("src") or tag.get("href") or ""
        if not src:
            continue
        lower = src.lower()
        if "/assets/" not in lower and "app." not in lower:
            continue
        if not lower.endswith(".js"):
            continue
        name = lower.rsplit("/", 1)[-1]
        if name.startswith("app.") or name.startswith("index.") or "app-" in name:
            candidates.append(src)

    # Preferir app.*.js
    candidates.sort(key=lambda s: (0 if "/app." in s.lower() or s.lower().rsplit("/", 1)[-1].startswith("app.") else 1, s))

    for src in candidates:
        absolute = urljoin(start_url, src)
        if absolute in seen:
            continue
        seen.add(absolute)
        urls.append(absolute)

    return urls


def routes_from_spa_bundle(js_text: str, start_url: str, base_path: str) -> list[str]:
    """
    Extrae rutas embebidas en bundles VuePress/VitePress (path:"/foo.html").
    Las paths absolutas del router se resuelven respecto al base_path de docs.
    """
    origin = f"{urlparse(start_url).scheme}://{urlparse(start_url).netloc}"
    base_root = base_path if base_path.endswith("/") else base_path + "/"

    paths = set(re.findall(r'\bpath\s*:\s*"([^"]+)"', js_text))
    paths |= set(re.findall(r"\bpath\s*:\s*'([^']+)'", js_text))

    # VitePress a veces usa path en arrays de sidebar como ["/guide/foo"]
    # Evitar ruido: solo paths que parecen docs (empiezan con / y tienen letras)
    found: list[str] = []
    seen: set[str] = set()

    for raw in paths:
        path = raw.strip()
        if not path or path in {"*", "/"}:
            if path == "/":
                absolute = normalize_url(urljoin(origin, base_root))
            else:
                continue
        elif path.startswith("http://") or path.startswith("https://"):
            absolute = normalize_url(path)
        elif path.startswith("/"):
            # VuePress base=/docs/latest/ → path /charts/bar.html ⇒ /docs/latest/charts/bar.html
            absolute = normalize_url(urljoin(origin, base_root.rstrip("/") + path))
        else:
            absolute = normalize_url(urljoin(origin + base_root, path))

        key = canonical_page_key(absolute)
        if key in seen:
            continue
        if not should_keep_link(absolute, start_url, base_path, "base"):
            continue
        seen.add(key)
        found.append(absolute)

    return found


def discover_pages(
    session: requests.Session,
    start_url: str,
    seed_html: str,
    scope: str,
    pause: float,
    crawl: bool = True,
    max_pages: int = 0,
) -> tuple[list[str], dict[str, str]]:
    """
    Descubre todas las páginas documentales.

    Fuentes:
      1) Enlaces del HTML semilla (nav + contenido)
      2) Rutas en bundles SPA (VuePress/VitePress app.js)
      3) Crawl BFS opcional siguiendo enlaces in-scope
    """
    base_path = docs_base_path(start_url)
    queue: deque[str] = deque()
    ordered: list[str] = []
    seen_keys: set[str] = set()
    html_cache: dict[str, str] = {}

    def enqueue(url: str, html: str | None = None) -> None:
        norm = normalize_url(url)
        key = canonical_page_key(norm)
        if key in seen_keys:
            if html is not None and norm not in html_cache:
                html_cache[norm] = html
            return
        seen_keys.add(key)
        ordered.append(norm)
        queue.append(norm)
        if html is not None:
            html_cache[norm] = html

    enqueue(start_url, seed_html)

    for link in extract_links_from_html(start_url, seed_html, base_path, scope):
        enqueue(link)

    # Bundles SPA en la página semilla
    for bundle_url in discover_spa_bundle_urls(start_url, seed_html):
        try:
            print(f"  Bundle SPA: {bundle_url}")
            response = fetch(session, bundle_url)
            for route in routes_from_spa_bundle(response.text, start_url, base_path):
                enqueue(route)
            time.sleep(max(pause, 0))
        except Exception as exc:  # noqa: BLE001
            print(f"  WARN bundle: {bundle_url} -> {exc}", file=sys.stderr)

    if not crawl:
        if max_pages > 0:
            return ordered[:max_pages], html_cache
        return ordered, html_cache

    # BFS: seguir enlaces de cada página
    while queue:
        if max_pages > 0 and len(ordered) >= max_pages:
            break

        current = queue.popleft()
        html = html_cache.get(current)
        if html is None:
            try:
                response = fetch(session, current)
                final_url = normalize_url(str(response.url))
                html = response.text
                html_cache[current] = html
                html_cache[final_url] = html
                # Si redirige a otra URL canónica, también encolarla
                if canonical_page_key(final_url) != canonical_page_key(current):
                    enqueue(final_url, html)
            except Exception as exc:  # noqa: BLE001
                print(f"  WARN fetch: {current} -> {exc}", file=sys.stderr)
                continue
            time.sleep(max(pause, 0))

        for link in extract_links_from_html(current, html, base_path, scope):
            if max_pages > 0 and len(ordered) >= max_pages:
                break
            enqueue(link)

        # Bundles SPA también en páginas interiores (por si el app.js solo está ahí)
        # Evitar re-descargar: solo si aún no vimos muchas rutas
        if len(ordered) < 20:
            for bundle_url in discover_spa_bundle_urls(current, html):
                try:
                    response = fetch(session, bundle_url)
                    for route in routes_from_spa_bundle(response.text, start_url, base_path):
                        enqueue(route)
                    time.sleep(max(pause, 0))
                except Exception:  # noqa: BLE001
                    pass

    if max_pages > 0:
        ordered = ordered[:max_pages]

    return ordered, html_cache


def fetch(session: requests.Session, url: str) -> requests.Response:
    last_error: Exception | None = None
    for attempt in range(1, MAX_RETRIES + 1):
        try:
            response = session.get(url, timeout=REQUEST_TIMEOUT, allow_redirects=True)
            if response.status_code == 403:
                time.sleep(attempt * 3)
                continue
            response.raise_for_status()
            return response
        except requests.RequestException as exc:
            last_error = exc
            if attempt == MAX_RETRIES:
                break
            time.sleep(attempt * 2)
    raise last_error or RuntimeError(f"No se pudo descargar {url}")


def page_to_markdown(url: str, html: str) -> tuple[str, str]:
    soup = BeautifulSoup(html, "html.parser")
    title = soup.title.get_text(strip=True) if soup.title else url
    title = re.sub(r"\s*[·|]\s*.*$", "", title).strip() or title
    # VuePress a menudo deja "Chart.js" genérico; preferir h1 del contenido
    h1 = soup.select_one(".theme-default-content h1, .content__default h1, main h1, article h1")
    if h1:
        h1_text = re.sub(r"^#\s*", "", h1.get_text(" ", strip=True)).strip()
        if h1_text and len(h1_text) < 120:
            title = h1_text

    content = extract_main_html(soup)
    if content is None:
        raise RuntimeError(f"Sin contenido extraíble: {url}")

    for tag in content.select(
        "nav, footer, header, script, style, form, aside.sidebar, .sidebar, [data-docs-toc], .page-nav, .page-edit"
    ):
        tag.decompose()

    markdown = clean_markdown(html_to_md(str(content), heading_style="ATX", bullets="-"))
    if len(markdown.strip()) < MIN_MARKDOWN_CHARS:
        raise RuntimeError(f"Contenido demasiado corto: {url}")

    header = textwrap.dedent(
        f"""\
        # {title}

        > Fuente: {url}
        > Exportado: {datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")}

        """
    )
    return title, header + markdown


def mdc_frontmatter(prefix: str, title: str, path_hint: str) -> str:
    safe_title = title.replace("\n", " ").strip()
    description = (
        f"Documentación local ({prefix}): {safe_title} ({path_hint}). "
        "Usar como referencia al implementar o depurar según esta documentación."
    )
    return (
        "---\n"
        "alwaysApply: false\n"
        f"description: {description}\n"
        "---\n\n"
    )


def resolve_skills_dir(output_root: Path, prefix: str, flat: bool) -> Path:
    """
    Por defecto: .cursor/skills/{prefix}/
    Con --flat:  .cursor/skills/  (legacy, todo mezclado)
    """
    if flat:
        return output_root
    return output_root / prefix


def write_index(
    output_dir: Path,
    prefix: str,
    start_url: str,
    exported: list[tuple[str, str, str]],
    missing: list[str],
) -> None:
    exported_at = datetime.now(timezone.utc).strftime("%Y-%m-%d %H:%M UTC")
    lines = [
        "---",
        "alwaysApply: false",
        f"description: Índice de documentación local ({prefix}) exportada desde {urlparse(start_url).netloc}.",
        "---",
        "",
        f"# {prefix} — documentación local",
        "",
        f"Exportada el **{exported_at}** desde [{start_url}]({start_url}).",
        "",
        f"Total exportado: **{len(exported)}** páginas.",
        "",
        f"Carpeta: `.cursor/skills/{prefix}/`",
        "",
        "## Cómo actualizar",
        "",
        "```bash",
        f"php artisan cursor:make:documentation {start_url} --prefix={prefix}",
        "```",
        "",
        "## Índice",
        "",
    ]
    for _url, title, filename in exported:
        lines.append(f"- [{title}]({filename})")

    lines.extend(["", "## No exportadas", ""])
    if missing:
        for item in missing:
            lines.append(f"- {item}")
    else:
        lines.append("- Ninguna.")

    (output_dir / f"{prefix}-docs.mdc").write_text("\n".join(lines) + "\n", encoding="utf-8")


def parse_args(argv: list[str] | None = None) -> argparse.Namespace:
    parser = argparse.ArgumentParser(
        description="Exporta docs web a .cursor/skills/{prefix}/*.mdc"
    )
    parser.add_argument("url", help="URL de una página de documentación")
    parser.add_argument("--prefix", default="", help="Prefijo / carpeta de skills (auto si se omite)")
    parser.add_argument(
        "--output",
        default=str(DEFAULT_OUTPUT),
        help="Raíz de skills (default: .cursor/skills); se crea subcarpeta {prefix}/",
    )
    parser.add_argument(
        "--flat",
        action="store_true",
        help="Escribir en --output sin subcarpeta por prefijo (legacy)",
    )
    parser.add_argument(
        "--scope",
        choices=("nav", "base"),
        default="nav",
        help="nav=enlaces del menú mismo host; base=solo bajo el path base de la URL",
    )
    parser.add_argument("--limit", type=int, default=0, help="Limitar páginas (0=todas)")
    parser.add_argument("--dry-run", action="store_true", help="Solo listar URLs")
    parser.add_argument(
        "--no-crawl",
        action="store_true",
        help="No hacer BFS; solo semilla + bundles SPA",
    )
    parser.add_argument(
        "--pause",
        type=float,
        default=REQUEST_PAUSE_SECONDS,
        help="Pausa entre requests (segundos)",
    )
    return parser.parse_args(argv)


def main(argv: list[str] | None = None) -> int:
    args = parse_args(argv)
    start_url = normalize_url(args.url)
    prefix = (args.prefix or infer_prefix(start_url)).strip().lower()
    prefix = re.sub(r"[^a-z0-9-]+", "-", prefix).strip("-")
    output_root = Path(args.output).expanduser().resolve()
    output_dir = resolve_skills_dir(output_root, prefix, flat=bool(args.flat))
    base_path = docs_base_path(start_url)

    print(f"URL inicial: {start_url}")
    print(f"Prefijo:     {prefix}")
    print(f"Base path:   {base_path}")
    print(f"Scope:       {args.scope}")
    print(f"Crawl BFS:   {not args.no_crawl}")
    print(f"Salida:      {output_dir}")

    session = build_session()
    seed = fetch(session, start_url)
    seed_url = normalize_url(str(seed.url))
    pages, html_cache = discover_pages(
        session=session,
        start_url=seed_url,
        seed_html=seed.text,
        scope=args.scope,
        pause=args.pause,
        crawl=not args.no_crawl,
        max_pages=args.limit if args.limit > 0 else 0,
    )

    # Asegurar HTML del seed
    html_cache[seed_url] = seed.text
    html_cache[start_url] = seed.text

    print(f"Páginas:     {len(pages)}")

    if args.dry_run:
        for page in pages:
            print(f"  {page}")
        return 0

    output_dir.mkdir(parents=True, exist_ok=True)

    exported: list[tuple[str, str, str]] = []
    missing: list[str] = []
    used_filenames: set[str] = set()

    for index, page_url in enumerate(pages, start=1):
        try:
            html = html_cache.get(page_url)
            final_url = page_url
            if html is None:
                response = fetch(session, page_url)
                final_url = normalize_url(str(response.url))
                html = response.text
                html_cache[page_url] = html
                html_cache[final_url] = html

            title, markdown = page_to_markdown(final_url, html)
            filename = filename_for(
                prefix,
                urlparse(final_url).path,
                base_path,
                urlparse(start_url).hostname or "",
                final_url,
            )
            # Evitar colisiones de nombre
            base_name = filename
            suffix = 2
            while f"{filename}.mdc" in used_filenames:
                filename = f"{base_name}-{suffix}"
                suffix += 1
            used_filenames.add(f"{filename}.mdc")

            outfile = output_dir / f"{filename}.mdc"
            outfile.write_text(
                mdc_frontmatter(prefix, title, urlparse(final_url).path) + markdown,
                encoding="utf-8",
            )
            exported.append((final_url, title, outfile.name))
            print(f"  OK  [{index}/{len(pages)}] {final_url} -> {prefix}/{outfile.name}")
        except Exception as exc:  # noqa: BLE001
            missing.append(f"`{page_url}` — {exc}")
            print(f"  ERR [{index}/{len(pages)}] {page_url} -> {exc}", file=sys.stderr)

        if index < len(pages):
            time.sleep(max(args.pause, 0))

    write_index(output_dir, prefix, start_url, exported, missing)

    print("")
    print(f"Exportadas: {len(exported)}")
    print(f"Fallidas:   {len(missing)}")
    print(f"Índice:     {output_dir / f'{prefix}-docs.mdc'}")

    return 0 if exported else 1


if __name__ == "__main__":
    raise SystemExit(main())
