# Laravel Sail

## Purpose
Docker-backed local stack. Prefer `./vendor/bin/sail` after install.

## Install
```bash
php artisan sail:install --with=mysql,redis
./vendor/bin/sail up -d
```

Minimum services: MySQL + Redis. SQLite may remain for quick host PHP runs.

## Common
```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm run dev
./vendor/bin/sail composer require ...
```

## Docs
https://laravel.com/docs/sail
