# Instalar Tailwind CSS con PostCSS

## Dependencias

```bash
npm install tailwindcss @tailwindcss/postcss postcss
```

## Configuración

```js
export default {
  plugins: {
    "@tailwindcss/postcss": {},
  },
}
```

## CSS principal

```css
@import "tailwindcss";
```

Usa esta integración cuando el proyecto ya tenga un pipeline PostCSS explícito. En un proyecto Laravel con Vite, normalmente se prefiere `@tailwindcss/vite`.

Fuente oficial: https://tailwindcss.com/docs/installation/using-postcss
