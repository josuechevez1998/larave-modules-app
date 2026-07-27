---
name: tailwindcss-v4
description: Implementar, mantener y revisar interfaces con Tailwind CSS v4.3 en Laravel, Blade, Livewire, Vite, JavaScript, TypeScript y componentes. Usar al instalar Tailwind, escribir clases, crear temas, variantes, utilidades, layouts responsivos, modo oscuro, animaciones o resolver problemas de detección de clases.
---

# Tailwind CSS v4.3

Esta skill contiene una adaptación técnica en español de la documentación oficial de Tailwind CSS v4.3, tomada como referencia el 2026-07-27.

## Cuándo usarla

Usa esta skill al:

- configurar Tailwind con Vite o PostCSS;
- modificar `vite.config.*` o la hoja CSS principal;
- crear o revisar clases en Blade, Livewire, Vue, React o HTML;
- definir tema, colores, tipografías, breakpoints o sombras;
- trabajar con variantes, dark mode o responsive design;
- crear utilidades o variantes personalizadas;
- resolver clases que no aparecen en el CSS compilado;
- migrar código de Tailwind v3 a v4.

## Procedimiento obligatorio

1. Identifica la tarea y abre solo las referencias relacionadas.
2. Para instalación en este proyecto, consulta `references/installation/using-vite.md`.
3. Para clases dinámicas, consulta `references/core-concepts/detecting-classes-in-source-files.md`.
4. Para tokens de diseño, consulta `references/core-concepts/theme.md`.
5. Para CSS personalizado, consulta `references/core-concepts/adding-custom-styles.md`.
6. Para directivas v4, consulta `references/core-concepts/functions-and-directives.md`.
7. Verifica que las clases sean cadenas completas y detectables.
8. Conserva accesibilidad, responsive design y estados de foco.
9. No introduzcas sintaxis de Tailwind v3 en archivos v4.

## Principios del proyecto

- Tailwind se integra mediante Vite.
- La configuración principal es CSS-first.
- Se prefieren utilidades y tokens semánticos.
- Los componentes reutilizables pueden encapsular patrones, pero no deben ocultar estados ni accesibilidad.
- No concatenar fragmentos de clases.
- No usar Play CDN en producción.
- No editar dependencias dentro de `node_modules`.

## Índices

- Instalación: `references/installation/index.md`
- Navegación completa: `references/INDEX.md`
- Manifiesto: `MANIFEST.md`
