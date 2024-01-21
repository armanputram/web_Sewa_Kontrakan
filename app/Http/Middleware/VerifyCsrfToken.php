<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*', // Contoh: Semua URI di bawah /api akan dikecualikan dari verifikasi CSRF
        '127.0.0.1:8000/api/loginpemilik',
        '127.0.0.1:8000/api/loginpenyewa',
        //
    ];
}
