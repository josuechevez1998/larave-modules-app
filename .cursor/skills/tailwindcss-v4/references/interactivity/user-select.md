# user-select

Controla interacción, selección, scroll, cursor y controles.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **user-select**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="select-none select-text select-all"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `user-select`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/user-select
