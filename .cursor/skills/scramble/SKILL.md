# Scramble (API docs)

## Purpose
OpenAPI docs for the Laravel API at `/docs/api` (default Scramble routes).

## Install
```bash
composer require dedoc/scramble
php artisan vendor:publish --provider="Dedoc\Scramble\ScrambleServiceProvider" --tag=scramble-config
```

## Access
Gate `viewApiDocs`: local always; otherwise Super Admin only (`AppServiceProvider`).

## Docs
https://scramble.dedoc.co/installation
