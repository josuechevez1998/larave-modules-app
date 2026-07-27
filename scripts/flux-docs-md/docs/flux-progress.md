# Progress

> Fuente: https://fluxui.dev/components/progress
> Exportado: 2026-07-10 22:31 UTC

# Progress

A simple progress bar to indicate completion or loading status.

```
<flux:progress value="75" />
```

## [Max](#max)

Set a custom maximum value with the max prop for non-percentage scales.

```
<flux:progress value="3" max="7" />
```

## [Color](#color)

Override the default accent color with the color prop.

```
<flux:progress value="75" color="purple" />
```

## [Height](#height)

Customize the height of the progress bar using the class prop.

```
<flux:progress value="75" class="h-3" />
```

## [With label](#with-label)

Wrap the progress bar in a flux:field to add a label and description. When used without a visible label, use sr-only on the label for screen reader accessibility.

Upload progress

Uploading 3 of 7 files...

```
<flux:field>    <flux:label>Upload progress</flux:label>    <flux:progress value="42" color="blue" />    <flux:description>Uploading 3 of 7 files...</flux:description></flux:field>
```

## [Display value](#display-value)

Display the current value in the label or inline next to the bar.

Storage

75%

Storage

75%

```
<flux:field>    <flux:label>        Storage        <x-slot name="trailing">            <span class="tabular-nums">75%</span>        </x-slot>    </flux:label>    <flux:progress value="75" /></flux:field><flux:field>    <flux:label>Storage</flux:label>    <div class="flex items-center gap-4 -mt-2">        <flux:progress value="75" />        <span class="text-sm tabular-nums ...">75%</span>    </div></flux:field>
```

## [Controlled](#controlled)

Bind the progress value to a property using wire:model to control it dynamically.

```
<flux:slider wire:model="progress" /><flux:progress wire:model="progress" />
```

## Related components

[Slider

Select a value using a horizontal slider control](/components/slider)
[Skeleton

Show placeholder content while loading](/components/skeleton)

## Reference

### [flux:progress](#fluxprogress)

| Prop | Description |
| --- | --- |
| value | Current progress value (0 to max). Default: 0. |
| max | Maximum value. Default: 100. |
| color | Bar fill color (e.g., blue, red, green). Default: accent color. |

| CSS Variable | Description |
| --- | --- |
| --flux-progress | The computed progress as a raw number (0–100). |
| --flux-progress-percentage | The computed progress as a percentage string (e.g., 75%). Used internally to set the bar width. |

 [Caleb Porzio](https://x.com/calebporzio) and [Hugo Sainte-Marie](https://x.com/hugosaintemarie)
