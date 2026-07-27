# Avatar

> Fuente: https://fluxui.dev/components/avatar
> Exportado: 2026-07-10 22:30 UTC

# Avatar

Display an image or initials as an avatar.

![](https://unavatar.io/x/calebporzio)

```
<flux:avatar src="https://unavatar.io/x/calebporzio" />
```

## [Tooltip](#tooltip)

Use the tooltip prop to display a tooltip when hovering over the avatar.

![](https://unavatar.io/x/calebporzio)

Caleb Porzio

```
<flux:avatar tooltip="Caleb Porzio" src="https://unavatar.io/x/calebporzio" /><!-- Or infer from the name prop... --><flux:avatar tooltip name="Caleb Porzio" src="https://unavatar.io/x/calebporzio" />
```

## [Initials](#initials)

When no src is provided, the name prop will be used to automatically generate initials. You can also use the initials prop directly.

CP

Ca

C

```
<flux:avatar name="Caleb Porzio" /><flux:avatar name="calebporzio" /><flux:avatar name="calebporzio" initials:single /><!-- Or use the initials prop directly... --><flux:avatar initials="CP" />
```

## [Size](#size)

Use the size prop to change the size of the avatar.

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/x/calebporzio)

```
<!-- Extra large: size-16 (64px) --><flux:avatar size="xl" src="https://unavatar.io/x/calebporzio" /><!-- Large: size-12 (48px) --><flux:avatar size="lg" src="https://unavatar.io/x/calebporzio" /><!-- Default: size-10 (40px) --><flux:avatar src="https://unavatar.io/x/calebporzio" /><!-- Small: size-8 (32px) --><flux:avatar size="sm" src="https://unavatar.io/x/calebporzio" /><!-- Extra small: size-6 (24px) --><flux:avatar size="xs" src="https://unavatar.io/x/calebporzio" />
```

## [Icon](#icon)

Use the icon prop to display an icon instead of an image.

```
<flux:avatar icon="user" /><flux:avatar icon="phone" /><flux:avatar icon="computer-desktop" />
```

## [Colors](#colors)

Use the color prop to change the color of the avatar.

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

CP

```
<flux:avatar name="Caleb Porzio" color="red" /><flux:avatar name="Caleb Porzio" color="orange" /><flux:avatar name="Caleb Porzio" color="amber" /><flux:avatar name="Caleb Porzio" color="yellow" /><flux:avatar name="Caleb Porzio" color="lime" /><flux:avatar name="Caleb Porzio" color="green" /><flux:avatar name="Caleb Porzio" color="emerald" /><flux:avatar name="Caleb Porzio" color="teal" /><flux:avatar name="Caleb Porzio" color="cyan" /><flux:avatar name="Caleb Porzio" color="sky" /><flux:avatar name="Caleb Porzio" color="blue" /><flux:avatar name="Caleb Porzio" color="indigo" /><flux:avatar name="Caleb Porzio" color="violet" /><flux:avatar name="Caleb Porzio" color="purple" /><flux:avatar name="Caleb Porzio" color="fuchsia" /><flux:avatar name="Caleb Porzio" color="pink" /><flux:avatar name="Caleb Porzio" color="rose" />
```

## [Auto color](#auto-color)

Deterministically generate a color based on a user's name.

CP

MJ

KC

KN

KS

BP

AB

```
<flux:avatar name="Caleb Porzio" color="auto" /><!-- Use color:seed to generate a consistent color based --><!-- on something unchanging like a user's ID... --><flux:avatar name="Caleb Porzio" color="auto" color:seed="{{ $user->id }}" />
```

## [Circle](#circle)

Use the circle prop to make the avatar circular.

![](https://unavatar.io/x/calebporzio)

```
<flux:avatar circle src="https://unavatar.io/x/calebporzio" />
```

## [Badge](#badge)

Add badges to avatars in various ways. Use the badge prop by itself for a simple dot indicator, provide content like numbers or emojis, or even pass in custom HTML via a slot.

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/x/calebporzio)

25

![](https://unavatar.io/x/calebporzio)

👍

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/github/hugosaintemarie)

```
<flux:avatar badge badge:color="green" src="https://unavatar.io/x/calebporzio" /><flux:avatar badge badge:color="zinc" badge:position="top right" badge:circle badge:variant="outline" src="https://unavatar.io/x/calebporzio" /><flux:avatar badge="25" src="https://unavatar.io/x/calebporzio" /><flux:avatar circle badge="👍" badge:circle src="https://unavatar.io/x/calebporzio" /><flux:avatar circle src="https://unavatar.io/x/calebporzio">    <x-slot:badge>        <img class="size-3" src="https://unavatar.io/github/hugosaintemarie" />    </x-slot:badge></flux:avatar>
```

## [Groups](#groups)

Stack avatars together. By default, grouped avatars have rings that adapt to your theme - white in light mode and a dark color in dark mode. If you need to customize the ring color, to match a different background, you can do so by adding a custom class to the flux:avatar.group component.

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/github/hugosaintemarie)

![](https://unavatar.io/github/joshhanley)

3+

![](https://unavatar.io/x/calebporzio)

![](https://unavatar.io/github/hugosaintemarie)

![](https://unavatar.io/github/joshhanley)

3+

```
<flux:avatar.group>    <flux:avatar src="https://unavatar.io/x/calebporzio" />    <flux:avatar src="https://unavatar.io/github/hugosaintemarie" />    <flux:avatar src="https://unavatar.io/github/joshhanley" />    <flux:avatar>3+</flux:avatar></flux:avatar.group><!-- Adapt rings to custom background... --><flux:avatar.group class="**:ring-zinc-100 dark:**:ring-zinc-800">    <flux:avatar circle src="https://unavatar.io/x/calebporzio" />    <flux:avatar circle src="https://unavatar.io/github/hugosaintemarie" />    <flux:avatar circle src="https://unavatar.io/github/joshhanley" />    <flux:avatar circle>3+</flux:avatar></flux:avatar.group>
```

## [As button](#as-button)

Use the as prop to make the avatar a button.

![](https://unavatar.io/x/calebporzio)

```
<flux:avatar as="button" src="https://unavatar.io/x/calebporzio" />
```

## [As link](#as-link)

Use the href prop to make the avatar a link.

[![](https://unavatar.io/x/calebporzio)](https://x.com/calebporzio)

```
<flux:avatar href="https://x.com/calebporzio" src="https://unavatar.io/x/calebporzio" />
```

## Examples

### [Testimonial](#testimonial)

IMO Livewire takes Blade to the next level. It's basically what Blade should be by default. 🔥

![](https://unavatar.io/x/taylorotwell)

Taylor Otwell

Creator of Laravel

```
<div>    <div class="flex items-center gap-2">        <flux:icon.star variant="solid" />        <flux:icon.star variant="solid" />        <flux:icon.star variant="solid" />        <flux:icon.star variant="solid" />        <flux:icon.star variant="solid" />    </div>    <flux:heading size="xl" class="mt-4 italic">        <p>IMO Livewire takes Blade to the next level. It's basically what Blade should be by default. 🔥</p>    </flux:heading>    <div class="mt-6 flex items-center gap-4">        <flux:avatar size="lg" src="https://unavatar.io/x/taylorotwell" />        <div>            <flux:heading size="lg">Taylor Otwell</flux:heading>            <flux:text>Creator of Laravel</flux:text>        </div>    </div></div>
```

### [Grouped feature](#grouped-feature)

The Laravel Podcast

A podcast about Laravel, development best practices, and the PHP ecosystem—hosted by Jeffrey Way, Matt Stauffer, and Taylor Otwell, later joined by Adam Wathan.

![](https://unavatar.io/x/taylorotwell)

![](https://unavatar.io/x/adamwathan)

![](https://unavatar.io/x/jeffrey_way)

![](https://unavatar.io/x/stauffermatt)

```
<div>    <flux:heading size="xl">The Laravel Podcast <flux:badge inset="top bottom" class="ml-1 max-sm:hidden">New</flux:badge></flux:heading>    <flux:text class="mt-2">        A podcast about Laravel, development best practices, and the PHP ecosystem—hosted by Jeffrey Way, Matt Stauffer, and Taylor Otwell, later joined by Adam Wathan.    </flux:text>    <flux:avatar.group class="mt-6">        <flux:avatar circle size="lg" src="https://unavatar.io/x/taylorotwell" />        <flux:avatar circle size="lg" src="https://unavatar.io/x/adamwathan" />        <flux:avatar circle size="lg" src="https://unavatar.io/x/jeffrey_way" />        <flux:avatar circle size="lg" src="https://unavatar.io/x/stauffermatt" />    </flux:avatar.group></div>
```

### [Members table](#members-table)

Include avatars in table rows to make user data more identifiable.

Team members

Invite

|  |  |
| --- | --- |
| Caleb Porzio You  [[email protected]](/cdn-cgi/l/email-protection) | Admin Member Guest |
| Hugo Sainte-Marie  [[email protected]](/cdn-cgi/l/email-protection) | Admin Member Guest |
| Josh Hanley  [[email protected]](/cdn-cgi/l/email-protection) | Admin Member Guest |

```
<div class="flex justify-between items-center mb-4">    <flux:heading size="lg">Team members</flux:heading>    <flux:button size="sm" icon="plus">Invite</flux:button></div><flux:table>    <flux:table.rows>        <flux:table.row>            <flux:table.cell>                <div class="flex items-center gap-2 sm:gap-4">                    <flux:avatar circle size="lg" class="max-sm:size-8" src="https://unavatar.io/github/calebporzio" />                    <div class="flex flex-col">                        <flux:heading>Caleb Porzio <flux:badge size="sm" color="blue" class="ml-1 max-sm:hidden">You</flux:badge></flux:heading>                        <flux:text class="max-sm:hidden">[email protected]</flux:text>                    </div>                </div>            </flux:table.cell>            <flux:table.cell>                <div class="flex justify-end items-center gap-2">                    <flux:select size="sm" class="min-w-fit max-w-fit">                        <flux:select.option value="admin" selected>Admin</flux:select.option>                        <flux:select.option value="member">Member</flux:select.option>                        <flux:select.option value="guest">Guest</flux:select.option>                    </flux:select>                    <flux:button size="sm" variant="subtle" icon="trash" class="shrink-0" />                </div>            </flux:table.cell>        </flux:table.row>        <flux:table.row >            <flux:table.cell>                <div class="flex items-center gap-2 sm:gap-4">                    <flux:avatar circle size="lg" class="max-sm:size-8" src="https://unavatar.io/github/hugosaintemarie" />                    <div class="flex flex-col">                        <flux:heading>Hugo Sainte-Marie</flux:heading>                        <flux:text class="max-sm:hidden">[email protected]</flux:text>                    </div>                </div>            </flux:table.cell>            <flux:table.cell>                <div class="flex justify-end items-center gap-2">                    <flux:select size="sm" class="min-w-fit max-w-fit">                        <flux:select.option value="admin">Admin</flux:select.option>                        <flux:select.option value="member" selected>Member</flux:select.option>                        <flux:select.option value="guest">Guest</flux:select.option>                    </flux:select>                    <flux:button size="sm" variant="subtle" icon="trash" class="shrink-0" />                </div>            </flux:table.cell>        </flux:table.row>        <flux:table.row>            <flux:table.cell>                <div class="flex items-center gap-2 sm:gap-4">                    <flux:avatar circle size="lg" class="max-sm:size-8" src="https://unavatar.io/github/joshhanley" />                    <div class="flex flex-col">                        <flux:heading>Josh Hanley</flux:heading>                        <flux:text class="max-sm:hidden">[email protected]</flux:text>                    </div>                </div>            </flux:table.cell>            <flux:table.cell>                <div class="flex justify-end items-center gap-2">                    <flux:select size="sm" class="min-w-fit max-w-fit">                        <flux:select.option value="admin">Admin</flux:select.option>                        <flux:select.option value="member" selected>Member</flux:select.option>                        <flux:select.option value="guest">Guest</flux:select.option>                    </flux:select>                    <flux:button size="sm" variant="subtle" icon="trash" class="shrink-0" />                </div>            </flux:table.cell>        </flux:table.row>    </flux:table.rows></flux:table>
```

### [Assignees list](#assignees-list)

Include avatars in table rows to make user data more identifiable.

Assignees

- ![](https://unavatar.io/github/calebporzio)

  Caleb Porzio
- ![](https://unavatar.io/github/hugosaintemarie)

  Hugo Sainte-Marie
- ![](https://unavatar.io/github/joshhanley)

  Josh Hanley
- ![](https://unavatar.io/github/jasonlbeggs)

  Jason Beggs

```
<flux:card>    <div class="flex justify-between items-center">        <flux:heading>Assignees</flux:heading>        <flux:button size="sm" variant="subtle" icon="plus" inset="top bottom" />    </div>    <flux:separator class="mt-2 mb-4" variant="subtle" />    <ul class="flex flex-col gap-3">        <li class="flex items-center gap-2">            <flux:avatar size="xs" src="https://unavatar.io/github/calebporzio" />            <flux:heading>Caleb Porzio</flux:heading>        </li>        <li class="flex items-center gap-2">            <flux:avatar size="xs" src="https://unavatar.io/github/hugosaintemarie" />            <flux:heading>Hugo Sainte-Marie</flux:heading>        </li>        <li class="flex items-center gap-2">            <flux:avatar size="xs" src="https://unavatar.io/github/joshhanley" />            <flux:heading>Josh Hanley</flux:heading>        </li>        <li class="flex items-center gap-2">            <flux:avatar size="xs" src="https://unavatar.io/github/jasonlbeggs" />            <flux:heading>Jason Beggs</flux:heading>        </li>    </ul></flux:card>
```

### [Select options](#select-options)

Enhance select options with avatars to make them more visually recognizable.

Assign to

selected

![](https://unavatar.io/github/calebporzio)

Caleb Porzio

![](https://unavatar.io/github/hugosaintemarie)

Hugo Sainte-Marie

![](https://unavatar.io/github/joshhanley)

Josh Hanley

![](https://unavatar.io/github/jasonlbeggs)

Jason Beggs

```
<flux:select variant="listbox" label="Assign to">    <flux:select.option selected>        <div class="flex items-center gap-2 whitespace-nowrap">            <flux:avatar circle size="xs" src="https://unavatar.io/github/calebporzio" /> Caleb Porzio        </div>    </flux:select.option>    <flux:select.option>        <div class="flex items-center gap-2 whitespace-nowrap">            <flux:avatar circle size="xs" src="https://unavatar.io/github/hugosaintemarie" /> Hugo Sainte-Marie        </div>    </flux:select.option>    <flux:select.option>        <div class="flex items-center gap-2 whitespace-nowrap">            <flux:avatar circle size="xs" src="https://unavatar.io/github/joshhanley" /> Josh Hanley        </div>    </flux:select.option>    <flux:select.option>        <div class="flex items-center gap-2 whitespace-nowrap">            <flux:avatar circle size="xs" src="https://unavatar.io/github/jasonlbeggs" /> Jason Beggs        </div>    </flux:select.option></flux:select>
```

### [User popover](#user-popover)

Display a user's profile information in a popover.

![calebporzio](https://unavatar.io/x/calebporzio)

Follow back

![calebporzio](https://unavatar.io/x/calebporzio)

Caleb Porzio

@calebporzio

Follows you

I'm a full stack developer with a passion for building web applications. Currently working on a new project called [Flux](https://fluxui.dev).

1.2k

Followers

1.2k

Following

```
<flux:dropdown hover position="bottom center">    <flux:avatar as="button" name="calebporzio" src="https://unavatar.io/x/calebporzio" />    <flux:popover class="relative max-w-[15rem]">        <div class="absolute top-0 right-0 p-2">            <flux:button icon="user-plus" variant="filled" size="sm">Follow back</flux:button>        </div>        <flux:avatar size="xl" name="calebporzio" src="https://unavatar.io/x/calebporzio" />        <flux:heading class="mt-2">Caleb Porzio</flux:heading>        <flux:text>@calebporzio <flux:badge color="zinc" size="sm" inset="top bottom">Follows you</flux:badge></flux:text>        <flux:text class="mt-3">            I'm a full stack developer with a passion for building web applications. Currently working on a new project called <flux:link href="https://fluxui.dev">Flux</flux:link>.        </flux:text>        <div class="flex gap-4 mt-3">            <div class="flex gap-2 items-center">                <flux:heading>1.2k</flux:heading> <flux:text>Followers</flux:text>            </div>            <div class="flex gap-2 items-center">                <flux:heading>1.2k</flux:heading> <flux:text>Following</flux:text>            </div>        </div>    </flux:popover></flux:dropdown>
```

## Related components

[Profile

Display a user's profile with avatar and optional name](/components/profile)
[Tooltip

Display informative tooltips when hovering over an element](/components/tooltip)

## Reference

### [flux:avatar](#fluxavatar)

| Prop | Description |
| --- | --- |
| name | User's name to display as initials. If provided without initials, this will be used to generate initials automatically. |
| src | URL to the image to display as avatar. |
| initials | Custom initials to display when no src is provided. Will override name if provided. |
| alt | Alternative text for the avatar image. (Default: name if provided) |
| size | Size of the avatar. Options: xs (24px), sm (32px), (default: 40px), lg (48px). |
| color | Background color when displaying initials or icons. Options: red, orange, amber, yellow, lime, green, emerald, teal, cyan, sky, blue, indigo, violet, purple, fuchsia, pink, rose, auto. Default: none (uses system colors). |
| color:seed | Value used when color="auto" to deterministically generate consistent colors. Useful for using user IDs to generate consistent colors. |
| circle | If present or true, makes the avatar fully circular instead of rounded corners. |
| icon | Name of the icon to display instead of an image or initials. |
| icon:variant | Icon variant to use. Options: outline, solid. Default: solid. |
| tooltip | Text to display in a tooltip when hovering over the avatar. If set to true, uses the name prop as tooltip content. |
| tooltip:position | Position of the tooltip. Options: top, right, bottom, left. Default: top. |
| badge | Content to display as a badge. Can be a string, boolean, or a slot. |
| badge:color | Color of the badge. Options: same color options as the color prop. |
| badge:circle | If present or true, makes the badge fully circular instead of slightly rounded corners. |
| badge:position | Position of the badge. Options: top left, top right, bottom left, bottom right. Default: bottom right. |
| badge:variant | Variant of the badge. Options: solid, outline. Default: solid. |
| as | Element to render the avatar as. Options: button, div (default). |
| href | URL to link to, making the avatar a link element. |

| Slot | Description |
| --- | --- |
| default | Custom content to display inside the avatar. Will override initials if provided. |
| badge | Custom content to display in the badge (for more complex badge content). |

### [flux:avatar.group](#fluxavatargroup)

| Prop | Description |
| --- | --- |
| class | CSS classes to apply to the group, including customizing ring colors using \*:ring-{color} format. |

| Slot | Description |
| --- | --- |
| default | Place multiple flux:avatar components here to display them as a group. |

 [Caleb Porzio](https://x.com/calebporzio) and [Hugo Sainte-Marie](https://x.com/hugosaintemarie)
