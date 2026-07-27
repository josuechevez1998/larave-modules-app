<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('auth.forgot_title')"
        :description="__('auth.forgot_description')"
    />

    <x-auth-session-status :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-5">
        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            name="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">
            {{ __('auth.send_reset_link') }}
        </flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-stone-600 rtl:space-x-reverse dark:text-stone-400">
        <span>{{ __('auth.or_return_to') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
