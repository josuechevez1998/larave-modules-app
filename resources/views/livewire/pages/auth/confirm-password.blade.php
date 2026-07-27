<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('auth.confirm_title')"
        :description="__('auth.confirm_description')"
    />

    <form wire:submit="confirmPassword" class="flex flex-col gap-5">
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            name="password"
            required
            autofocus
            autocomplete="current-password"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full">
            {{ __('Confirm') }}
        </flux:button>
    </form>
</div>
