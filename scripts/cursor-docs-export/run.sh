#!/usr/bin/env bash
# Atajo opcional; preferir: php artisan cursor:make:documentation <url>
set -euo pipefail
ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT"

need_recreate=0
if [[ ! -d .venv ]]; then
  need_recreate=1
elif ! .venv/bin/python -c 'import pip' >/dev/null 2>&1; then
  need_recreate=1
fi

if [[ "$need_recreate" -eq 1 ]]; then
  rm -rf .venv
  if ! python3 -m venv --upgrade-deps .venv 2>/dev/null; then
    python3 -m venv .venv
  fi
fi

if ! .venv/bin/python -c 'import pip' >/dev/null 2>&1; then
  .venv/bin/python -m ensurepip --upgrade 2>/dev/null || true
fi

if ! .venv/bin/python -c 'import pip' >/dev/null 2>&1; then
  curl -fsSL https://bootstrap.pypa.io/get-pip.py -o get-pip.py
  .venv/bin/python get-pip.py --force-reinstall
  rm -f get-pip.py
fi

.venv/bin/python -m pip install --upgrade pip --quiet || true
.venv/bin/python -m pip install -r requirements.txt --quiet
exec .venv/bin/python export_docs.py "$@"
