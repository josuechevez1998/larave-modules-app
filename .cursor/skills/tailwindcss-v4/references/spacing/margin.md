# margin

Controla espacio interior y exterior usando la escala del tema.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **margin**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="m-4 mx-auto mt-6 -ml-2"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `margin`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/margin
