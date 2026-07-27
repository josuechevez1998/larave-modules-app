#!/usr/bin/env bash
set -euo pipefail
cd /home/guanacodev/modular-app
export PATH="${HOME}/.config/herd-lite/bin:${HOME}/.phpenv/shims:/usr/local/bin:${PATH}"

RESULT=stack-install-result.txt
echo "=== stack install $(date -Iseconds) ===" > "$RESULT"

run() {
  local label="$1"; shift
  echo ">> $label" | tee -a "$RESULT"
  if "$@" >>"$RESULT" 2>&1; then
    echo "OK: $label" | tee -a "$RESULT"
  else
    echo "FAIL: $label (exit $?)" | tee -a "$RESULT"
  fi
}

run "composer update" composer update --no-interaction -W
run "npm install" npm install
run "publish crud" php artisan vendor:publish --tag=crud --no-interaction --force
run "publish stubs-crud" php artisan vendor:publish --tag=stubs-crud --no-interaction --force
run "publish breadcrumbs-config" php artisan vendor:publish --provider="Diglactic\Breadcrumbs\ServiceProvider" --tag=breadcrumbs-config --no-interaction --force
run "publish scramble" php artisan vendor:publish --provider="Dedoc\Scramble\ScrambleServiceProvider" --tag=scramble-config --no-interaction --force
run "log-viewer publish" php artisan log-viewer:publish --no-interaction
run "migrate" php artisan migrate --force
run "seed statuses" php artisan db:seed --class=Database\\Seeders\\StatusSeeder --force
run "npm build" npm run build

{
  echo "=== versions ==="
  composer show livewire/flux diglactic/laravel-breadcrumbs opcodesio/log-viewer dedoc/scramble ibex/crud-generator 2>&1 | head -40
  echo "=== flux vendor ==="
  test -d vendor/livewire/flux && echo flux_ok || echo flux_missing
  echo "=== node ==="
  test -d node_modules/flowbite && echo flowbite_ok || echo flowbite_missing
  test -d node_modules/@tailwindcss/vite && echo tw_vite_ok || echo tw_vite_missing
} | tee -a "$RESULT"

echo DONE >> "$RESULT"
