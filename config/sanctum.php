<?php

use Laravel\Sanctum\Sanctum;

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive stateful API
    | authentication cookies. Typically, these should include your local
    | and production domains which access your API via a frontend SPA.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        Sanctum::currentApplicationUrlWithPort()
    ))),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | This array contains the authentication guards that will be checked when
    | Sanctum is trying to authenticate a request. If none of these guards
    | are able to authenticate the request, Sanctum will use the bearer
    | token that's present on an incoming request for authentication.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'prefix' => 'api',

        'api' => [
            'driver' => 'token',
            'provider' => 'registrasi_pemilik',
        ],

        'registrasi_pemilik' => [
            'driver' => 'sanctum',
            'provider' => 'registrasi_pemilik',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until an issued token will be
    | considered expired. If this value is null, personal access tokens do
    | not expire. This won't tweak the lifetime of first-party sessions.
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | When authenticating your first-party SPA with Sanctum you may need to
    | customize some of the middleware Sanctum uses while processing the
    | request. You may change the middleware listed below as required.
    |
    */

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
        'can_login' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Sanctum Models
    |--------------------------------------------------------------------------
    |
    | These are the Eloquent models that will be used to store personal access
    | tokens, as well as to interact with your database's "users" table.
    | You may override these models as needed, but the default models work
    | well out of the box and shouldn't need to be changed. If you do change
    | these models, you should definitely run your "sanctum:install" Artisan
    | command to ensure your database is set up to use the new models.
    |
    */

    'models' => [
        'personal_access_token' => \App\Models\RegistrasiPemilik::class,
    ],
    'model' => [
        'personal_access_token' => \App\Models\RegistrasiPenyewa::class,
    ],

];
