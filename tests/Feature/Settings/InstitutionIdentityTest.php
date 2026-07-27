<?php

namespace Tests\Feature\Settings;

use App\Livewire\Settings\InstitutionIdentity;
use App\Models\InstitutionSetting;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class InstitutionIdentityTest extends TestCase
{
    use RefreshDatabase;

    private function userWithInstitutionPermission(): User
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        setPermissionsTeamId(null);

        Permission::query()->updateOrCreate(
            ['name' => 'settings.institution', 'guard_name' => 'web'],
            ['module' => 'Core']
        );

        $role = Role::query()->firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            'team_id' => null,
        ]);

        $role->givePermissionTo('settings.institution');

        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    public function test_institution_page_requires_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('settings.institution'))
            ->assertForbidden();
    }

    public function test_institution_identity_can_be_updated(): void
    {
        $user = $this->userWithInstitutionPermission();

        $this->actingAs($user);

        Livewire::test(InstitutionIdentity::class)
            ->set('name', 'Institución Demo')
            ->set('tagline', 'Servicio al ciudadano')
            ->set('support_email', 'soporte@example.com')
            ->call('save')
            ->assertHasNoErrors();

        $settings = InstitutionSetting::query()->first();

        $this->assertNotNull($settings);
        $this->assertSame('Institución Demo', $settings->name);
        $this->assertSame('Servicio al ciudadano', $settings->tagline);
        $this->assertSame('soporte@example.com', $settings->support_email);
    }

    public function test_appearance_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('settings.appearance'))
            ->assertOk();
    }
}
