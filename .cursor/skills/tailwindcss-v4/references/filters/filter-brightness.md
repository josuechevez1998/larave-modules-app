# brightness

Controla filtros CSS y filtros aplicados al fondo.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **brightness**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="brightness-75 brightness-100 brightness-125"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `filter-brightness`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/filter-brightness
