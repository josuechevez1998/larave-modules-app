# Context menu

> Fuente: https://fluxui.dev/components/context
> Exportado: 2026-07-10 22:31 UTC

Flux Pro component

This component is only available in the Pro version of Flux.

[Upgrade to Pro ->](/pricing)
[Upgrade to Pro ->](/pricing)

# Context menu

Dropdown menus that open when right clicking a designated area.

Learn more about the menu component on the [dropdown page ->](/components/dropdown)

Right click

New post
Sort by

Name
Date
Popularity

Filter

Draft
Published
Archived

Delete

```
<flux:context>    <flux:card class="border-dashed border-2 px-16">        <flux:text>Right click</flux:text>    </flux:card>    <flux:menu>        <flux:menu.item icon="plus">New post</flux:menu.item>        <flux:menu.separator />        <flux:menu.submenu heading="Sort by">            <flux:menu.radio.group>                <flux:menu.radio checked>Name</flux:menu.radio>                <flux:menu.radio>Date</flux:menu.radio>                <flux:menu.radio>Popularity</flux:menu.radio>            </flux:menu.radio.group>        </flux:menu.submenu>        <flux:menu.submenu heading="Filter">            <flux:menu.checkbox checked>Draft</flux:menu.checkbox>            <flux:menu.checkbox checked>Published</flux:menu.checkbox>            <flux:menu.checkbox>Archived</flux:menu.checkbox>        </flux:menu.submenu>        <flux:menu.separator />        <flux:menu.item variant="danger" icon="trash">Delete</flux:menu.item>    </flux:menu></flux:context>
```

## Related components

[Dropdown

Display expandable menus for navigational options](/components/dropdown)
[Command palette

Execute actions with keyboard shortcuts](/components/command)

## Reference

### [flux:context](#fluxcontext)

A wrapper component that enables right-click context menu functionality.

| Prop | Description |
| --- | --- |
| wire:model | Binds the context menu's state to a Livewire property. See the [wire:model documentation](https://livewire.laravel.com/docs/wire-model) for more information. |
| position | Controls the position of the menu relative to the click position. Format: [vertical] [horizontal]. Vertical options: top, bottom (default). Horizontal options: start, center, end (default). |
| gap | Distance in pixels between the menu and the click position. Default: 4. |
| offset | Additional offset in pixels along both axes. Format: [x] [y]. |
| target | ID of an external element to use as the menu. Use this when you need the menu to be outside the context element's DOM tree. |
| detail | Custom value to be stored in the menu's data-detail attribute, useful for adding custom styling or behavior based on the source of the context menu. |
| disabled | Prevents the context menu from being shown when right-clicking. |

| Slot | Description |
| --- | --- |
| default | The first child element functions as the trigger area, which will show the context menu when right-clicked. The second child element should be a flux:menu component that will appear as the context menu. |

 [Caleb Porzio](https://x.com/calebporzio) and [Hugo Sainte-Marie](https://x.com/hugosaintemarie)
