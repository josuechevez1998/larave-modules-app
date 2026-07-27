<?php

namespace App\Services;

use App\Models\InstitutionSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class InstitutionSettingService
{
    /**
     * @param  array{
     *     name: string,
     *     tagline?: string|null,
     *     support_email?: string|null,
     *     phone?: string|null,
     *     mobile?: string|null,
     *     social_links?: list<array{name: string, url: string}>|null,
     * }  $data
     */
    public function update(InstitutionSetting $settings, array $data, ?UploadedFile $logo = null): InstitutionSetting
    {
        $logoPath = $settings->logo_path;

        if ($logo) {
            if (filled($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            $logoPath = $logo->store('institution', 'public');
        }

        $settings->update([
            'name' => $data['name'],
            'tagline' => filled($data['tagline'] ?? null) ? $data['tagline'] : null,
            'support_email' => filled($data['support_email'] ?? null) ? $data['support_email'] : null,
            'phone' => filled($data['phone'] ?? null) ? $data['phone'] : null,
            'mobile' => filled($data['mobile'] ?? null) ? $data['mobile'] : null,
            'social_links' => $data['social_links'] ?? [],
            'logo_path' => $logoPath,
        ]);

        return $settings->refresh();
    }

    public function removeLogo(InstitutionSetting $settings): InstitutionSetting
    {
        if (filled($settings->logo_path)) {
            Storage::disk('public')->delete($settings->logo_path);
            $settings->update(['logo_path' => null]);
        }

        return $settings->refresh();
    }
}
