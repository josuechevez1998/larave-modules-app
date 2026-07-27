# Skeleton

> Fuente: https://fluxui.dev/components/skeleton
> Exportado: 2026-07-10 22:31 UTC

# Skeleton

Create placeholder content while loading data.

```
<flux:skeleton.group animate="shimmer" class="flex items-center gap-4">    <flux:skeleton class="size-10 rounded-full" />    <div class="flex-1">        <flux:skeleton.line />        <flux:skeleton.line class="w-1/2" />    </div></flux:skeleton.group>
```

## [Line of text](#line-of-text)

Create a loading state for a single line of text. The flux:skeleton.line component occupies the full spatial line-height (e.g., ~20px) while rendering a slimmer bar, giving it the visual proportions of real text.

```
<flux:skeleton.group animate="shimmer">    <flux:skeleton.line class="mb-2 w-1/4" />    <flux:skeleton.line />    <flux:skeleton.line />    <flux:skeleton.line class="w-3/4" /></flux:skeleton.group>
```

## [Animation](#animation)

Use the animate prop to add an animation to the skeleton.

None

Shimmer

Pulse

```
<flux:skeleton /><flux:skeleton animate="shimmer" /><flux:skeleton animate="pulse" />
```

## [Examples](#examples)

Here are some examples of different ways you can use the skeleton component.

### [Table](#table)

Use the skeleton component to create a loading state for a table.

| Customer | Date | Status | Amount |
| --- | --- | --- | --- |
|  |  |  |  |
|  |  |  |  |
|  |  |  |  |
|  |  |  |  |
|  |  |  |  |

```
<flux:skeleton.group animate="shimmer">    <flux:table>        <flux:table.columns>            <flux:table.column>Customer</flux:table.column>            <flux:table.column>Date</flux:table.column>            <flux:table.column>Status</flux:table.column>            <flux:table.column>Amount</flux:table.column>        </flux:table.columns>        <flux:table.rows>            @foreach (range(1, 5) as $order)                <flux:table.row>                    <flux:table.cell>                        <div class="flex items-center gap-2">                            <flux:skeleton class="rounded-full size-5" />                            <div class="flex-1">                                <flux:skeleton.line style="width: {{ rand(50, 100) }}%" />                            </div>                        </div>                    </flux:table.cell>                    <flux:table.cell>                        <flux:skeleton.line />                    </flux:table.cell>                    <flux:table.cell>                        <flux:skeleton.line />                    </flux:table.cell>                    <flux:table.cell>                        <flux:skeleton.line />                    </flux:table.cell>                </flux:table.row>            @endforeach        </flux:table.rows>    </flux:table></flux:skeleton.group>
```

### [Chart](#chart)

Use the skeleton component to create a loading state for a chart.

Today

$---

-:-- PM

Yesterday

$---

```
<flux:card class="dark:bg-zinc-800">    <div class="flex flex-col gap-6">        <div class="flex gap-12">            <div>                <flux:text>Today</flux:text>                <flux:heading size="xl" class="mt-2 tabular-nums">$---</flux:heading>                <flux:text class="mt-2 tabular-nums">-:-- PM</flux:text>            </div>            <div>                <flux:text>Yesterday</flux:text>                <flux:heading size="lg" class="mt-2 tabular-nums">$---</flux:heading>            </div>        </div>        <flux:skeleton animate="shimmer" class="aspect-[4/1] size-full rounded-lg" />    </div></flux:card>
```

## Reference

### [flux:skeleton](#fluxskeleton)

| Prop | Description |
| --- | --- |
| animate | Animation style for the skeleton. Options: shimmer, pulse. Defaults to no animation. |

| Slot | Description |
| --- | --- |
| default | The skeleton elements (lines, boxes, circles). |

| CSS Variable | Description |
| --- | --- |
| --flux-shimmer-color | The background color used for the shimmer animation. Defaults to white in light mode and var(--color-zinc-900) in dark mode. Set this to match your page's background color to prevent the shimmer from showing through gaps between skeleton elements. |

| Attribute | Description |
| --- | --- |
| data-flux-skeleton | Applied to the root element for styling and identification. |

### [flux:skeleton.line](#fluxskeletonline)

| Prop | Description |
| --- | --- |
| size | Size of the line. Options: base, lg. Defaults to base. |
| animate | Animation style for the skeleton. Options: shimmer, pulse. Defaults to no animation. Can be inherited from parent flux:skeleton.group. |

| Attribute | Description |
| --- | --- |
| data-flux-skeleton-line | Applied to the root element for styling and identification. |

### [flux:skeleton.group](#fluxskeletongroup)

| Prop | Description |
| --- | --- |
| animate | Animation style for all skeleton elements within the group. Options: shimmer, pulse. Defaults to no animation. This value is inherited by child flux:skeleton and flux:skeleton.line components. |

| Attribute | Description |
| --- | --- |
| data-flux-skeleton-group | Applied to the root element for styling and identification. |

 [Caleb Porzio](https://x.com/calebporzio) and [Hugo Sainte-Marie](https://x.com/hugosaintemarie)
