<?php

namespace App\Livewire\Settings;

use App\Services\UserProfileService;
use App\Support\Locale\SupportedLocales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Language extends Component
{
    public string $locale = SupportedLocales::DEFAULT;

    public function mount(): void
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $this->locale = SupportedLocales::isValid($user->locale ?? null)
            ? $user->locale
            : SupportedLocales::DEFAULT;
    }

    public function updateLanguage(UserProfileService $profiles): void
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $validated = $this->validate([
            'locale' => ['required', 'string', Rule::in(SupportedLocales::all())],
        ]);

        $profiles->updateLocale($user, $validated['locale']);

        $this->redirect(route('settings.language'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.settings.language')
            ->layout('components.layouts.app');
    }
}
