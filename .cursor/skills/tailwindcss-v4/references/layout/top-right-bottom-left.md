# top / right / bottom / left

Controla el flujo, posicionamiento, desbordamiento y comportamiento de caja.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **top / right / bottom / left**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="inset-0 top-4 right-0 -left-2"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `top-right-bottom-left`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/top-right-bottom-left
