# Spatie Laravel Permission v8 — archivos MDC

Este paquete contiene una adaptación técnica en español de la documentación oficial de `spatie/laravel-permission` v8.

## Formato

Cada página se convirtió en un archivo `.mdc` con:

- frontmatter compatible con reglas de Cursor;
- objetivo de la página;
- reglas y conceptos clave;
- ejemplos mínimos cuando corresponde;
- precauciones;
- enlace a la página oficial y al archivo del repositorio.

## Uso en Cursor

Puedes copiar toda la carpeta dentro de:

```text
.cursor/rules/spatie-laravel-permission-v8/
```

Todos los archivos usan `alwaysApply: false`, por lo que Cursor debería cargarlos según el contexto. Puedes cambiar archivos específicos a `alwaysApply: true` si necesitas que una regla sea permanente.

## Nota de licencia y contenido

Los archivos son resúmenes y adaptaciones originales. Para detalles completos, casos especiales y cambios posteriores, consulta siempre las fuentes oficiales incluidas en cada archivo.
