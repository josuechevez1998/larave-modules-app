# Modal

> Fuente: https://fluxui.dev/components/modal
> Exportado: 2026-07-10 22:31 UTC

# Modal

Display content in a layer above the main page. Ideal for confirmations, alerts, and forms.

Edit profile

```
<flux:modal.trigger name="edit-profile">    <flux:button>Edit profile</flux:button></flux:modal.trigger><flux:modal name="edit-profile" class="md:w-96">    <div class="space-y-6">        <div>            <flux:heading size="lg">Update profile</flux:heading>            <flux:text class="mt-2">Make changes to your personal details.</flux:text>        </div>        <flux:input label="Name" placeholder="Your name" />        <flux:input label="Date of birth" type="date" />        <div class="flex">            <flux:spacer />            <flux:button type="submit" variant="primary">Save changes</flux:button>        </div>    </div></flux:modal>
```

## [Unique modal names](#unique-modal-names)

If you are placing modals inside a loop, ensure that you are dynamically generating unique modal names. Otherwise, one modal trigger, will trigger all modals of that name on the page causing unexpected behavior.

```
@foreach ($users as $user)    <flux:modal :name="'edit-profile-'.$user->id">        ...    </flux:modal>@endforeach
```

## [Livewire methods](#livewire-methods)

In addition to triggering modals in your Blade templates, you can also control them directly from Livewire.

Consider a "confirm" modal in your Blade template like so:

```
<flux:modal name="confirm">    <!-- ... --></flux:modal>
```

You can now open and close this modal from your Livewire component using the following methods:

```
<?phpclass ShowPost extends \Livewire\Component {    public function delete() {        // Control "confirm" modals anywhere on the page...        Flux::modal('confirm')->show();        Flux::modal('confirm')->close();        // Control "confirm" modals within this Livewire component...        $this->modal('confirm')->show();        $this->modal('confirm')->close();        // Closes all modals on the page...        Flux::modals()->close();    }}
```

## [JavaScript methods](#javascript-methods)

You can also control modals from Alpine directly using Flux's magic methods:

```
<button x-on:click="$flux.modal('confirm').show()">    Open modal</button><button x-on:click="$flux.modal('confirm').close()">    Close modal</button><button x-on:click="$flux.modals().close()">    Close all modals</button>
```

Or you can use the window.Flux global object to control modals from any JavaScript in your application:

```
// Control "confirm" modals anywhere on the page...Flux.modal('confirm').show()Flux.modal('confirm').close()// Closes all modals on the page...Flux.modals().close()
```

## [Data binding](#data-binding)

If you prefer, you can bind a Livewire property directly to a modal to control its states from your Livewire component.

Consider a confirmation modal in your Blade template like so:

```
<flux:modal wire:model.self="showConfirmModal">    <!-- ... --></flux:modal>
```

It's important to add the .self modifier to the wire:model attribute to prevent nested elements from dispatching input events that would interfere with the state of the modal.

You can now open and close this modal from your Livewire component by toggling the wire:model property.

```
<?phpclass ShowPost extends \Livewire\Component {    public $showConfirmModal = false;    public function delete() {        $this->showConfirmModal = true;    }}
```

One advantage of this approach is being able to control the state of the modal directly from the browser without making a server roundtrip:

```
<flux:button x-on:click="$wire.showConfirmModal = true">Delete post</flux:button>
```

## [Close events](#close-events)

If you need to perform some logic after a modal closes, you can register a close listener like so:

```
<flux:modal @close="someLivewireAction">    <!-- ... --></flux:modal>
```

You can also use wire:close or x-on:close if you prefer those syntaxes.

## [Cancel events](#cancel-events)

If you need to perform some logic after a modal is cancelled by clicking outside or pressing escape, you can register a cancel listener like so:

```
<flux:modal @cancel="someLivewireAction">    <!-- ... --></flux:modal>
```

You can also use wire:cancel or x-on:cancel if you prefer those syntaxes.

## [Disable click outside](#disable-click-outside)

By default, clicking outside the modal will close it. If you want to disable this behavior, you can use the :dismissible="false" prop.

```
<flux:modal :dismissible="false">    <!-- ... --></flux:modal>
```

## [Scrolling long content](#scrolling-long-content)

By default, long modal content scrolls within the modal itself, making it easy to miss content below the fold. Set scroll="body" to let the modal extend beyond the viewport, making it obvious that content continues below.

```
<flux:modal name="terms" scroll="body">    <!-- Long-form content like terms of service... --></flux:modal>
```

## [Confirmation](#confirmation)

Prompt a user for confirmation before performing a dangerous action.

Delete

Delete project?

You're about to delete this project.  
This action cannot be reversed.

Cancel
Delete project

```
<flux:modal.trigger name="delete-profile">    <flux:button variant="danger">Delete</flux:button></flux:modal.trigger><flux:modal name="delete-profile" class="min-w-[22rem]">    <div class="space-y-6">        <div>            <flux:heading size="lg">Delete project?</flux:heading>            <flux:text class="mt-2">                You're about to delete this project.<br>                This action cannot be reversed.            </flux:text>        </div>        <div class="flex gap-2">            <flux:spacer />            <flux:modal.close>                <flux:button variant="ghost">Cancel</flux:button>            </flux:modal.close>            <flux:button type="submit" variant="danger">Delete project</flux:button>        </div>    </div></flux:modal>
```

## [Flyout](#flyout)

Use the flyout prop for a more anchored and long-form dialog.

Edit profile

Update profile

Make changes to your personal details.

Name

Date of birth

Save changes

```
<flux:modal.trigger name="edit-profile">    <flux:button>Edit profile</flux:button></flux:modal.trigger><flux:modal name="edit-profile" flyout>    <div class="space-y-6">        <div>            <flux:heading size="lg">Update profile</flux:heading>            <flux:text class="mt-2">Make changes to your personal details.</flux:text>        </div>        <flux:input label="Name" placeholder="Your name" />        <flux:input label="Date of birth" type="date" />        <div class="flex">            <flux:spacer />            <flux:button type="submit" variant="primary">Save changes</flux:button>        </div>    </div></flux:modal>
```

## Flyout positioning

By default, flyouts will open from the right. You can change this behavior by passing "left", or "bottom" into the position prop.

```
<flux:modal flyout position="left">    <!-- ... --></flux:modal>
```

### [Floating flyout](#floating-flyout)

Use the "floating" variant to give your flyout modal a floating appearance.

Edit profile

Update profile

Make changes to your personal details.

Name

Date of birth

```
<flux:modal.trigger name="edit-profile">    <flux:button>Edit profile</flux:button></flux:modal.trigger><flux:modal name="edit-profile" flyout variant="floating" class="md:w-lg">    <div class="space-y-6">        <flux:heading size="lg">Update profile</flux:heading>        <flux:subheading>Make changes to your personal details.</flux:subheading>        <flux:input label="Name" placeholder="Your name" />        <flux:input label="Date of birth" type="date" />    </div>    <x-slot name="footer" class="flex items-center justify-end gap-2">        <flux:modal.close>            <flux:button variant="filled">Cancel</flux:button>        </flux:modal.close>        <flux:button type="submit" variant="primary">Save changes</flux:button>    </x-slot></flux:modal>
```

## Related components

[Dropdown

Display expandable action or navigation menus](/components/dropdown)
[Command

Keyboard command palettes for application navigation](/components/command)

## Reference

### [flux:modal](#fluxmodal)

| Prop | Description |
| --- | --- |
| name | Unique identifier for the modal. Required when using triggers. |
| flyout | If true, the modal will open as a flyout. |
| variant | Visual style of the modal. Options: default, floating, bare (legacy: flyout). |
| position | For flyout modals, the direction they open from. Options: right (default), left, bottom. |
| scroll | Scrolling behavior for long content. Options: body. When set to body, the entire viewport scrolls instead of clipping overflow. |
| dismissible | If false, prevents closing the modal by clicking outside. Default: true. |
| closable | If false, hides the close button. Default: true. |
| wire:model | Optional Livewire property to bind the modal's open state to. |

| Event | Description |
| --- | --- |
| close | Triggered when the modal is closed by any means. |
| cancel | Triggered when the modal is closed by clicking outside or pressing escape. |

| Slot | Description |
| --- | --- |
| default | The modal content. |

| Class | Description |
| --- | --- |
| w-\* | Common use: md:w-96 for width. |

### [flux:modal.trigger](#fluxmodaltrigger)

| Prop | Description |
| --- | --- |
| name | Name of the modal to trigger. Must match the modal's name. |
| shortcut | Keyboard shortcut to open the modal (e.g., cmd.k). |

| Slot | Description |
| --- | --- |
| default | The trigger element (e.g., button). |

### [flux:modal.close](#fluxmodalclose)

| Slot | Description |
| --- | --- |
| default | The close trigger element (e.g., button). |

### [Flux::modal()](#fluxmodal)

PHP method for controlling modals from Livewire components.

| Parameter | Description |
| --- | --- |
| default|name | Name of the modal to control. |

| Method | Description |
| --- | --- |
| close() | Closes the modal. |

### [Flux::modals()](#fluxmodals)

PHP method for controlling all modals on the page.

| Method | Description |
| --- | --- |
| close() | Closes all modals on the page. |

### [$flux.modal()](#fluxmodal)

Alpine.js magic method for controlling modals.

| Parameter | Description |
| --- | --- |
| default|name | Name of the modal to control. |

| Method | Description |
| --- | --- |
| show() | Shows the modal. |
| close() | Closes the modal. |

 [Caleb Porzio](https://x.com/calebporzio) and [Hugo Sainte-Marie](https://x.com/hugosaintemarie)
