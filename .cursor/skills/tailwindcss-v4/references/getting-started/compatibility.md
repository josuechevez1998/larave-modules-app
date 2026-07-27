# Compatibility

Tailwind CSS v4 usa características de CSS moderno y está orientado a navegadores modernos.

## Reglas

- Compatibilidad base oficial: Chrome 111+, Safari 16.4+ y Firefox 128+.
- Evita Sass, Less y Stylus; Tailwind v4 ya procesa imports, nesting, variables y prefijos.
- En CSS Modules o estilos scoped, usa `@reference` para compartir tema y permitir `@apply`.
- Prefiere variables CSS del tema cuando solo necesitas consumir tokens.

## Fuente oficial

https://tailwindcss.com/docs/compatibility
