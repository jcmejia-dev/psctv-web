<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class CheckStaffModuleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $moduleKey): Response
    {
        $user = auth()->user();

        // Allow admins to access everything
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // Check if staff has access to the module
        if ($user && $user->role === 'staff') {
            if (!$user->hasModuleAccess($moduleKey)) {
                abort(403, 'You do not have access to this module.');
            }
        }

        return $next($request);
    }
}
