# Autocomplete

> Fuente: https://fluxui.dev/components/autocomplete
> Exportado: 2026-07-10 22:30 UTC

Flux Pro component

This component is only available in the Pro version of Flux.

[Upgrade to Pro ->](/pricing)
[Upgrade to Pro ->](/pricing)

# Autocomplete

Enhance an input field with autocomplete suggestions.

Autocomplete suggests text as you type and inserts the selected suggestion directly into the input field. It does not support a value attribute.

If you need to show a label but store a value (e.g., showing a user's name but storing their ID), use a [combobox](/components/select#combobox) instead.

State of residence

Alabama

Arkansas

California

Colorado

Connecticut

Delaware

Florida

Georgia

Hawaii

Idaho

Illinois

Indiana

Iowa

Kansas

Kentucky

Louisiana

Maine

Maryland

Massachusetts

Michigan

Minnesota

Mississippi

Missouri

Montana

Nebraska

Nevada

New Hampshire

New Jersey

New Mexico

New York

North Carolina

North Dakota

Ohio

Oklahoma

Oregon

Pennsylvania

Rhode Island

South Carolina

South Dakota

Tennessee

Texas

Utah

Vermont

Virginia

Washington

West Virginia

Wisconsin

Wyoming

```
<flux:autocomplete wire:model="state" label="State of residence">    <flux:autocomplete.item>Alabama</flux:autocomplete.item>    <flux:autocomplete.item>Arkansas</flux:autocomplete.item>    <flux:autocomplete.item>California</flux:autocomplete.item>    <!-- ... --></flux:autocomplete>
```

## Related components

[Input

Text fields for collecting user input](/components/input)
[Select

Display a dropdown menu for selecting one option from many](/components/select)

## Reference

### [flux:autocomplete](#fluxautocomplete)

| Prop | Description |
| --- | --- |
| wire:model | The name of the Livewire property to bind the input value to. |
| type | HTML input type (e.g., text, email, password, file, date). Default: text. |
| label | Label text displayed above the input. |
| description | Descriptive text displayed below the label. |
| placeholder | Placeholder text displayed when the input is empty. |
| size | Size of the input. Options: sm, xs. |
| variant | Visual style variant. Options: filled. Default: outline. |
| disabled | If true, prevents user interaction with the input. |
| readonly | If true, makes the input read-only. |
| invalid | If true, applies error styling to the input. |
| multiple | For file inputs, allows selecting multiple files. |
| mask | Input mask pattern using Alpine's mask plugin. Example: 99/99/9999. |
| icon | Name of the icon displayed at the start of the input. |
| icon:trailing | Name of the icon displayed at the end of the input. |
| kbd | Keyboard shortcut hint displayed at the end of the input. |
| clearable | If true, displays a clear button when the input has content. |
| copyable | If true, displays a copy button to copy the input's content. |
| viewable | For password inputs, if true, displays a toggle to show/hide the password. |
| as | Render the input as a different element. Options: button. Default: input. |
| container:class | Additional CSS classes applied to the autocomplete container. Useful for setting height constraints like max-h-80. |
| input:class | CSS classes applied directly to the input element instead of the input wrapper. |

| Slot | Description |
| --- | --- |
| icon | Custom content displayed at the start of the input (e.g., icons). |
| icon:leading | Custom content displayed at the start of the input (e.g., icons). |
| icon:trailing | Custom content displayed at the end of the input (e.g., buttons). |

### [flux:autocomplete.item](#fluxautocompleteitem)

| Prop | Description |
| --- | --- |
| disabled | If present or true, the item cannot be selected. |

 [Caleb Porzio](https://x.com/calebporzio) and [Hugo Sainte-Marie](https://x.com/hugosaintemarie)
