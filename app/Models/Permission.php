<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // Hereda $guarded = []; incluye `module` vía mass assignment.
}
