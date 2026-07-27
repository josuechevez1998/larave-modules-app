# Flux (Livewire UI)

## Purpose
Flux is the primary UI component library for authenticated Livewire screens (forms, overlays, buttons, toasts).

## Install
```bash
composer require livewire/flux
```

Requires **Tailwind CSS v4** (`@tailwindcss/vite`) and Flux CSS import in `resources/css/app.css`.

## Layout
In authenticated layouts:
- `@fluxAppearance` in `<head>`
- `@fluxScripts` before `</body>`
- Optional `@persist('toast')` + `<flux:toast />`

## Rules
- Prefer Flux components for forms and feedback.
- Do not load DaisyUI.
- Flowbite is JS-only for gaps Flux free does not cover.

## Docs
https://fluxui.dev/docs
