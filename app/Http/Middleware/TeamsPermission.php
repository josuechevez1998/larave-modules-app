<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamsPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $teamId = session('team_id') ?? $request->user()->current_team_id;

            if ($teamId) {
                setPermissionsTeamId($teamId);
            }
        }

        return $next($request);
    }
}
