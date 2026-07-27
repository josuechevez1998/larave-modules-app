# Lucide icons

## Purpose
All UI icons must come from Lucide (Blade `x-icon` or SVG helpers). Do not mix Heroicons/Font Awesome unless migrating legacy Breeze chrome.

## Install
```bash
npm install lucide lucide-static
```

## Usage
```blade
<x-icon name="home" class="w-5 h-5" />
```

Helper: `App\Support\LucideIcon::svg($name, $class)` reads `node_modules/lucide-static/icons/{name}.svg`.

## Docs
https://lucide.dev/icons/
