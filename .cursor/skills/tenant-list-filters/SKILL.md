---
name: tenant-list-filters
description: >-
  Añade o revisa filtros de listados Livewire del tenant (ui-filter, catálogos,
  clearFilters, Session). Usar al trabajar en Index de casos, expedientes,
  formularios, reportes, usuarios o cuando el usuario pida filtros por estado,
  tipo de caso, rol o catálogo.
---

# Filtros en listados tenant

## Cuándo aplicar

Al crear o ampliar un `*Index` Livewire del tenant con barra de filtros.

## Checklist

1. Revisar regla `.cursor/rules/filtros-listados-tenant.mdc`.
2. Copiar patrón de `FormTemplates\Index` / `CasesLegals\Index` (no inventar UI).
3. Props `#[Session]` + `updating*` → `resetPage()` + `clearFilters()`.
4. Selects alimentados desde catálogos activos (`state = true`).
5. Aplicar filtros en query base compartida con export si existe.
6. Actualizar `wire:target` / `filter-loading` / `index-busy` con los nuevos props.
7. Traducciones en `resources/lang/es.json`.
8. Pest: filtrar, combinar filtros, `clearFilters`.

## Casos legales

Obligatorios: `stateFilter` (`CatCasesState`) + `primaryTypeFilter` (`TypesCasesLegal` vía `is_primary`).

Coordinar con `showArchived` / `CaseArchiveSupport::archivedStateId()`.

## Anti-patrones

- Solo search cuando el módulo tiene estado/tipo/rol de catálogo.
- Filtrar en Blade o con `request()` suelto en vez de props Livewire.
- Olvidar resetear filtros nuevos en `clearFilters()`.
