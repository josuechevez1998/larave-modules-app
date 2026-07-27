# pointer-events

Controla interacción, selección, scroll, cursor y controles.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **pointer-events**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="pointer-events-none pointer-events-auto"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `pointer-events`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/pointer-events
