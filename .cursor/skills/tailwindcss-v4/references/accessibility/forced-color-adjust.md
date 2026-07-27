# forced-color-adjust

Controla comportamiento relacionado con modos de accesibilidad.

## Uso en el proyecto

- Consulta esta referencia cuando necesites trabajar con **forced-color-adjust**.
- Aplica variantes como `hover:`, `focus:`, `dark:` y breakpoints cuando el diseño lo requiera.
- Usa valores del tema antes de recurrir a valores arbitrarios.
- Un valor arbitrario debe escribirse como una clase completa y estática para que Tailwind pueda detectarlo.
- Evita construir nombres de clases mediante concatenación de fragmentos.

## Clases frecuentes

```html
<div class="forced-color-adjust-auto forced-color-adjust-none"></div>
```

## Valores arbitrarios

Cuando la escala no cubra el caso, utiliza la sintaxis arbitraria documentada para `forced-color-adjust`, por ejemplo una clase completa con `[...]`.

## Fuente oficial

https://tailwindcss.com/docs/forced-color-adjust
