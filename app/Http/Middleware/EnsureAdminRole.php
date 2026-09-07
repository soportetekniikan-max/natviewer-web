<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user =
            $request->user();

        if (
            ! $user
            || ! $user->is_admin
        ) {
            abort(403);
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (
            empty($roles)
            || ! in_array(
                $user->effectiveAdminRole(),
                $roles,
                true
            )
        ) {
            abort(403);
        }

        return $next($request);
    }
}