#!/usr/bin/env bash
set -euo pipefail
cd /home/guanacodev/modular-app

echo "=== Composer packages ==="
composer require livewire/flux diglactic/laravel-breadcrumbs opcodesio/log-viewer dedoc/scramble --no-interaction -W
composer require ibex/crud-generator --dev --no-interaction -W

echo "=== NPM ==="
npm uninstall @tailwindcss/forms autoprefixer postcss 2>/dev/null || true
npm install
npm install -D @tailwindcss/vite@^4 tailwindcss@^4
npm install flowbite lucide

echo "=== Publish ==="
php artisan vendor:publish --tag=crud --no-interaction || true
php artisan vendor:publish --tag=stubs-crud --no-interaction || true
php artisan vendor:publish --provider="Diglactic\Breadcrumbs\ServiceProvider" --tag=breadcrumbs-config --no-interaction || true
php artisan vendor:publish --provider="Diglactic\Breadcrumbs\ServiceProvider" --tag=breadcrumbs-routes --no-interaction || true
php artisan log-viewer:publish --no-interaction || true
php artisan vendor:publish --provider="Dedoc\Scramble\ScrambleServiceProvider" --tag="scramble-config" --no-interaction || true

echo "=== Sail ==="
# Non-interactive sail install: mysql redis
php artisan sail:install --with=mysql,redis --no-interaction 2>&1 || true

echo "=== DONE packages ==="
composer show livewire/flux | head -5
composer show diglactic/laravel-breadcrumbs | head -3
composer show opcodesio/log-viewer | head -3
composer show dedoc/scramble | head -3
