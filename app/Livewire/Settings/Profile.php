<?php

namespace App\Livewire\Settings;

use App\Services\UserProfileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';

    public string $email = '';

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfileInformation(UserProfileService $profiles): void
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique($user::class)->ignore($user->id),
            ],
        ]);

        $profiles->updateProfile($user, $validated);

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();
        abort_unless($user, 403);

        if (! method_exists($user, 'hasVerifiedEmail') || $user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    public function render(): View
    {
        return view('livewire.settings.profile')
            ->layout('components.layouts.app');
    }
}
