<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckStaffRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and has the 'staff' role
        if (auth()->check() && auth()->user()->role && auth()->user()->role->name === 'staff') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}



    

