<?php

// 252410102015 - Fatimah Azzahro

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');                 // cegah clickjacking
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        // CSP: hanya script/style dari domain sendiri; script inline (hasil XSS) ditolak browser
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; "
            ."form-action 'self'; frame-ancestors 'none'; base-uri 'self'; object-src 'none'");
        header_remove('X-Powered-By');                                       // sembunyikan versi PHP

        return $response;
    }
}
