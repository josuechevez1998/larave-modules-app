#!/usr/bin/env bash
set -euo pipefail
cd /home/guanacodev/modular-app

echo "=== routes log-viewer ==="
./vendor/bin/sail artisan route:list --path=log-viewer 2>&1 | head -20
echo "=== routes docs ==="
./vendor/bin/sail artisan route:list --path=docs 2>&1 | head -20

echo "=== counts ==="
./vendor/bin/sail artisan tinker --execute='echo "statuses=".App\Models\Status::count().PHP_EOL; echo "users=".App\Models\User::count().PHP_EOL;'

echo "=== packages ==="
./vendor/bin/sail composer show livewire/flux --format=json 2>/dev/null | head -c 200; echo
./vendor/bin/sail php -r 'foreach(["livewire/flux","diglactic/laravel-breadcrumbs","opcodesio/log-viewer","dedoc/scramble","ibex/crud-generator"] as $p){echo $p."=".(is_dir("vendor/".explode("/",$p)[0]."/".explode("/",$p)[1])?"yes":"no").PHP_EOL;}'

echo "=== locale ==="
./vendor/bin/sail artisan tinker --execute='echo config("app.locale")."/".config("app.fallback_locale").PHP_EOL;'

echo "SMOKE_OK"
