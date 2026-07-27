# Flowbite (JS only)

## Purpose
Use Flowbite **JavaScript** for native dropdowns/modals/tooltips where Flux free does not cover. Do **not** load Flowbite CSS or the Tailwind plugin.

## Install
```bash
npm install flowbite
```

## Usage
In `resources/js/app.js`:
```js
import { initFlowbite } from 'flowbite';
document.addEventListener('DOMContentLoaded', () => initFlowbite());
document.addEventListener('livewire:navigated', () => initFlowbite());
```

## Forbidden
- `@plugin "flowbite/plugin"`
- Full Flowbite CSS themes
- DaisyUI

## Docs
https://flowbite.com/docs/getting-started/quickstart/
