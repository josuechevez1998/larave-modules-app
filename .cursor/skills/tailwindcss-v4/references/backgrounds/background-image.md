# background-image

Controla fondos, gradientes, imágenes, posición y recorte.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **background-image**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="bg-none bg-linear-to-r from-sky-500 to-indigo-500"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `background-image`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/background-image
