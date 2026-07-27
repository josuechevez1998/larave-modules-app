<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;

class Team extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    protected static function booted(): void
    {
        static::created(function (Team $team): void {
            if (! Role::query()->where('name', 'Super Admin')->whereNull('team_id')->exists()) {
                return;
            }

            $sessionTeamId = getPermissionsTeamId();
            setPermissionsTeamId($team->id);

            User::query()
                ->where('email', 'admin@saas.test')
                ->each(function (User $admin) use ($team): void {
                    $admin->teams()->syncWithoutDetaching([$team->id]);

                    if (! $admin->hasRole('Super Admin')) {
                        $admin->assignRole('Super Admin');
                    }

                    if (! $admin->current_team_id) {
                        $admin->forceFill(['current_team_id' => $team->id])->save();
                    }
                });

            setPermissionsTeamId($sessionTeamId);
        });
    }
}
