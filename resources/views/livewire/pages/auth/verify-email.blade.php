<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('auth.verify_title')"
        :description="__('auth.verify_description')"
    />

    @if (session('status') == 'verification-link-sent')
        <flux:callout variant="success">
            {{ __('auth.verify_link_sent') }}
        </flux:callout>
    @endif

    <div class="flex flex-col items-stretch gap-3">
        <flux:button variant="primary" wire:click="sendVerification" class="w-full">
            {{ __('auth.resend_verification') }}
        </flux:button>

        <flux:button variant="ghost" wire:click="logout" type="button" class="w-full">
            {{ __('Log Out') }}
        </flux:button>
    </div>
</div>
