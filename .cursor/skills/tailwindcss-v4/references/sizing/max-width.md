# max-width

Controla dimensiones físicas y lógicas.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **max-width**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="max-w-md max-w-7xl max-w-none"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `max-width`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/max-width
