<?php

namespace App\Services;

use App\Models\User;
use App\Support\Locale\SupportedLocales;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserProfileService
{
    /**
     * @param  array{name: string, email: string}  $data
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user->refresh();
    }

    public function updateLocale(User $user, string $locale): User
    {
        if (! SupportedLocales::isValid($locale)) {
            throw ValidationException::withMessages([
                'locale' => [__('validation.in', ['attribute' => 'locale'])],
            ]);
        }

        $user->forceFill(['locale' => $locale])->save();

        session(['locale' => $locale]);
        App::setLocale($locale);

        return $user->refresh();
    }

    /**
     * @param  array{current_password: string, password: string}  $data
     */
    public function updatePassword(User $user, array $data): void
    {
        if (! Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => [__('auth.password')],
            ]);
        }

        $user->update([
            'password' => $data['password'],
        ]);
    }
}
