<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStaffPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $permission
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = Auth::user();

        // Check if user is authenticated and is staff
        if (!$user || $user->role !== 'staff') {
            abort(403, 'Unauthorized access.');
        }

        // Check if user has the specific permission
        if (!$user->hasStaffPermission($permission)) {
            abort(403, 'You do not have permission to access this feature.');
        }

        return $next($request);
    }
}
