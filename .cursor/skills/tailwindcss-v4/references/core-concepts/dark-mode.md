# Dark mode

La variante `dark:` permite estilos oscuros mediante preferencias del sistema o un selector personalizado.

## Reglas

- Por defecto, `dark:` puede responder a `prefers-color-scheme`.
- Para modo manual, redefine la variante con `@custom-variant dark (&:where(.dark, .dark *));`.
- También puedes usar un atributo, por ejemplo `[data-theme=dark]`.
- Evita duplicar componentes completos; cambia únicamente los tokens o utilidades necesarias.

## Fuente oficial

https://tailwindcss.com/docs/dark-mode
