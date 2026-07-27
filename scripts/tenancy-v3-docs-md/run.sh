#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT"

if ! command -v python3 >/dev/null 2>&1; then
  echo "python3 no está instalado." >&2
  exit 1
fi

VENV_DIR="$ROOT/.venv"

if [[ ! -d "$VENV_DIR" ]]; then
  echo "Creando entorno virtual en .venv ..."
  python3 -m venv "$VENV_DIR"
fi

# shellcheck disable=SC1091
source "$VENV_DIR/bin/activate"

python -m pip install --upgrade pip --quiet
python -m pip install -r requirements.txt --quiet
python export-tenancy-docs-from-web.py
