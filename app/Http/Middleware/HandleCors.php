<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\HandleCors as Middleware;

class HandleCors extends Middleware
{
    /**
     * The paths that should be excluded from CORS handling.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Example: 'api/no-cors/*',
    ];
}
