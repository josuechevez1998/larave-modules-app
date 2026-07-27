# Theme variables

Define tokens de diseño como variables de tema CSS que generan utilidades y variantes.

## Reglas

- Declara tokens dentro de `@theme`.
- Usa namespaces como `--color-*`, `--font-*`, `--spacing-*`, `--radius-*` y `--breakpoint-*`.
- Usa `@theme inline` cuando una variable dependa de otra variable CSS.
- Usa `@theme static` cuando necesites emitir todas las variables aunque no se detecten clases.
- Centraliza la marca en tokens; no disperses valores hexadecimales por las vistas.

## Fuente oficial

https://tailwindcss.com/docs/theme
