<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Periksa token pada setiap permintaan
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token tidak ditemukan'], 401);
        }

        // Pisahkan bagian token dari format "15|n8OaAxuei0IraLWOyN2IPHic897AP7cXso24Afy4361669c8"
        $tokenParts = explode('|', $token);
        $actualToken = end($tokenParts);

        // Lakukan verifikasi token dengan custom auth
        $user = DB::table('registrasi_pemilik')
            ->where('api_token', $actualToken)
            ->first();

        if (!$user) {
            Log::error('Token tidak valid: ' . $token);
            return response()->json([
                'error' => 'Token tidak valid',
                'token yang didapat' => $token
            ], 401);
        }

        // Jika perlu, Anda dapat menyimpan informasi user ke dalam request
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
