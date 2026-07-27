@props([
    'delayMs' => 180,
])

{{--
  Barra de ocupación del panel autenticado (no login).
  Delay para evitar parpadeo en requests muy cortos.
--}}
<div
    {{ $attributes->class('pointer-events-none fixed inset-x-0 top-0 z-[100]') }}
    x-data="{
        busy: false,
        timer: null,
        delay: {{ (int) $delayMs }},
        start() {
            clearTimeout(this.timer);
            this.timer = setTimeout(() => { this.busy = true }, this.delay);
        },
        stop() {
            clearTimeout(this.timer);
            this.timer = null;
            this.busy = false;
        },
        bindLivewire() {
            if (! window.Livewire || this._bound) {
                return;
            }
            this._bound = true;
            Livewire.hook('request', ({ respond }) => {
                this.start();
                respond(() => this.stop());
            });
        },
    }"
    x-init="
        bindLivewire();
        document.addEventListener('livewire:init', () => bindLivewire());
    "
    x-on:livewire:navigate.window="start()"
    x-on:livewire:navigated.window="stop()"
    x-on:livewire:navigate-error.window="stop()"
    aria-live="polite"
    aria-busy="false"
    x-bind:aria-busy="busy.toString()"
>
    <div
        x-cloak
        x-show="busy"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="h-0.5 w-full overflow-hidden bg-stone-200/70 dark:bg-stone-700/70"
    >
        <div class="ui-livewire-busy-bar h-full w-1/3 rounded-full bg-teal-600 dark:bg-teal-500"></div>
    </div>
</div>
