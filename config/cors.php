<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    | Apply CORS to these routes only. Usually API routes and Sanctum cookies.
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    | Allow all standard HTTP methods.
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    | Explicitly list all frontend origins you want to allow.
    | ⚠️ Do NOT use '*' if supports_credentials is true.
    */
    'allowed_origins' => [
        // Development
        'http://localhost:19006',     // Expo Web
        'http://127.0.0.1:19006',     // Expo Web (alternate)
        'http://localhost:8081',      // React Native Web (Expo dev server)
        'http://127.0.0.1:8081',      // Alternate React Native Web
        'http://localhost:5173',      // Vite/React Web dev
        'http://10.0.2.2:19006',      // Android emulator
        'http://10.0.22.243:19006',   // LAN IP dev testing
        'exp://127.0.0.1:*',          // Expo Go dev tunnel

        // Production
        'https://psctv.tech',
        'https://www.psctv.tech',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origin Patterns
    |--------------------------------------------------------------------------
    | For wildcard domains (optional)
    */
    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    | Allow all headers for now (you can restrict later)
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    | How long preflight results can be cached
    */
    'max_age' => 3600,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    | Must be true if using cookies, sessions, or Sanctum auth
    */
    'supports_credentials' => true,
];
