# Log Viewer

## Purpose
Browse Laravel logs in the browser (`/log-viewer`).

## Install
```bash
composer require opcodesio/log-viewer
php artisan log-viewer:publish
```

## Access
Configure authorization via gate `viewLogViewer` (local or Super Admin). Set in `config/log-viewer.php` `middleware` / `authorization` as published.

## Docs
https://github.com/opcodesio/log-viewer
