# Tailwind CLI

## Dependencias

```bash
npm install tailwindcss @tailwindcss/cli
```

## CSS de entrada

```css
@import "tailwindcss";
```

## Compilar en modo observación

```bash
npx @tailwindcss/cli -i ./src/input.css -o ./src/output.css --watch
```

La CLI es útil cuando no existe Vite, PostCSS u otro bundler.

Fuente oficial: https://tailwindcss.com/docs/installation/tailwind-cli
