<?php

namespace App\Livewire\Settings;

use App\Models\InstitutionSetting;
use App\Services\InstitutionSettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class InstitutionIdentity extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $tagline = '';

    public string $support_email = '';

    public string $phone = '';

    public string $mobile = '';

    /** @var list<array{name: string, url: string}> */
    public array $social_links = [];

    public $logo = null;

    public ?string $currentLogoUrl = null;

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('settings.institution'), 403);

        $settings = InstitutionSetting::current();

        $this->name = (string) ($settings->name ?: $settings->resolvedName());
        $this->tagline = (string) ($settings->tagline ?: ($settings->resolvedTagline() ?? ''));
        $this->support_email = (string) ($settings->support_email ?: ($settings->resolvedSupportEmail() ?? ''));
        $this->phone = (string) ($settings->phone ?? '');
        $this->mobile = (string) ($settings->mobile ?? '');
        $this->social_links = $settings->normalizedSocialLinks();
        $this->currentLogoUrl = $settings->resolvedLogoUrl();
    }

    public function addSocialLink(): void
    {
        $this->social_links[] = ['name' => '', 'url' => ''];
    }

    public function removeSocialLink(int $index): void
    {
        unset($this->social_links[$index]);
        $this->social_links = array_values($this->social_links);
    }

    public function removeLogo(InstitutionSettingService $service): void
    {
        abort_unless(Auth::user()?->can('settings.institution'), 403);

        $settings = $service->removeLogo(InstitutionSetting::current());

        $this->logo = null;
        $this->currentLogoUrl = $settings->resolvedLogoUrl();

        session()->flash('status', __('app.logo_removed'));
    }

    public function save(InstitutionSettingService $service): void
    {
        abort_unless(Auth::user()?->can('settings.institution'), 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'social_links' => ['nullable', 'array'],
            'social_links.*.name' => ['required', 'string', 'max:100'],
            'social_links.*.url' => [
                'required',
                'string',
                'max:255',
                'url',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! is_string($value) || ! str_starts_with(strtolower($value), 'https://')) {
                        $fail(__('app.social_https_required'));
                    }
                },
            ],
            'logo' => ['nullable', 'image', 'max:2048'],
        ], [
            'social_links.*.name.required' => __('app.social_name_required'),
            'social_links.*.url.required' => __('app.social_url_required'),
            'social_links.*.url.url' => __('app.social_url_invalid'),
        ]);

        $settings = $service->update(
            InstitutionSetting::current(),
            $validated,
            $this->logo,
        );

        $this->logo = null;
        $this->social_links = $settings->normalizedSocialLinks();
        $this->currentLogoUrl = $settings->resolvedLogoUrl();

        session()->flash('status', __('app.institution_updated'));
    }

    public function render(): View
    {
        return view('livewire.settings.institution-identity')
            ->layout('components.layouts.app');
    }
}
