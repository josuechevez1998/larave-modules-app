<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('auth.login_title')"
        :description="__('auth.login_description')"
    />

    <x-auth-session-status :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-5">
        <flux:input
            wire:model="form.email"
            :label="__('Email')"
            type="email"
            name="email"
            required
            autofocus
            autocomplete="username"
            placeholder="email@example.com"
        />

        <div class="relative">
            <flux:input
                wire:model="form.password"
                :label="__('Password')"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                viewable
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute end-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('auth.forgot_password_link') }}
                </flux:link>
            @endif
        </div>

        <flux:checkbox wire:model="form.remember" :label="__('Remember me')" />

        <flux:button variant="primary" type="submit" class="w-full">
            {{ __('Log in') }}
        </flux:button>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-center text-sm text-stone-600 rtl:space-x-reverse dark:text-stone-400">
            <span>{{ __('auth.no_account') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('auth.sign_up') }}</flux:link>
        </div>
    @endif
</div>
