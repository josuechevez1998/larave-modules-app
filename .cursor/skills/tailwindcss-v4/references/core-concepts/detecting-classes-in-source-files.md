# Detecting classes in source files

Tailwind escanea archivos como texto y genera CSS para candidatos de clase completos.

## Reglas

- Escribe nombres de clase completos; no construyas fragmentos dinámicamente.
- En Blade/PHP, mapea estados a cadenas completas como `bg-red-600` o `bg-green-600`.
- Usa `@source` para registrar rutas no detectadas automáticamente.
- Usa `source(none)` cuando quieras desactivar detección automática y declarar fuentes explícitas.
- Usa safelisting inline solo para clases generadas fuera de archivos escaneables.

## Fuente oficial

https://tailwindcss.com/docs/detecting-classes-in-source-files
