# Instalar Tailwind CSS con Vite

## Dependencias

```bash
npm install tailwindcss @tailwindcss/vite
```

## Configuración de Vite

```ts
import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    tailwindcss(),
  ],
})
```

En Laravel conserva también el plugin de Laravel y agrega `tailwindcss()` al arreglo de plugins.

## CSS principal

```css
@import "tailwindcss";
```

## Compilación

```bash
npm run dev
```

## Reglas

- No uses las directivas `@tailwind base`, `@tailwind components` y `@tailwind utilities` de v3.
- No instales `autoprefixer` solo por Tailwind v4; el pipeline moderno lo gestiona.
- Comprueba que la hoja CSS principal esté incluida por Vite.
- Usa clases completas y detectables en Blade, Livewire, JavaScript y componentes.

Fuente oficial: https://tailwindcss.com/docs/installation/using-vite
