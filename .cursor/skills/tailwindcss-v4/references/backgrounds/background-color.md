# background-color

Controla fondos, gradientes, imágenes, posición y recorte.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **background-color**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="bg-white bg-slate-900 bg-sky-500/50"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `background-color`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/background-color
