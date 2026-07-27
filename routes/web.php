<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\InstitutionIdentity;
use App\Livewire\Settings\Language;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function (): void {
    Route::redirect('settings', '/settings/profile');
    Route::redirect('profile', '/settings/profile')->name('profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/language', Language::class)->name('settings.language');
    Route::get('settings/institution', InstitutionIdentity::class)
        ->middleware('can:settings.institution')
        ->name('settings.institution');
});

require __DIR__.'/auth.php';
