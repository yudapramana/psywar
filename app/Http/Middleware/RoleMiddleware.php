<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Belum login
        if (! $request->user()) {
            abort(403, 'Unauthorized');
        }

        $user = $request->user();

        // Tidak punya role
        if (! $user->role) {
            abort(403, 'Role not assigned');
        }

        $userRole = $user->role->name;

        // SUPERADMIN selalu boleh
        if ($userRole === 'superadmin') {
            return $next($request);
        }

        // Jika route mendefinisikan role tertentu
        if (! empty($roles) && ! in_array($userRole, $roles)) {
            abort(403, 'Access denied');
        }

        return $next($request);
    }
}
