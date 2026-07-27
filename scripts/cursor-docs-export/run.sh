#!/usr/bin/env bash
# Atajo opcional; preferir: php artisan cursor:make:documentation <url>
set -euo pipefail
ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT"

if [[ ! -d .venv ]]; then
  python3 -m venv .venv
fi

.venv/bin/python -m pip install --upgrade pip --quiet
.venv/bin/python -m pip install -r requirements.txt --quiet
exec .venv/bin/python export_docs.py "$@"
