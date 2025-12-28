<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevenir MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevenir clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // Proteção XSS (legado, mas ainda útil)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Forçar HTTPS (HSTS) - apenas em produção
        if (app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content Security Policy - restritivo para API
        $response->headers->set('Content-Security-Policy', "default-src 'none'; frame-ancestors 'none'");

        // Prevenir informações do servidor
        $response->headers->set('X-Powered-By', 'Secure API');

        // Política de referrer
        $response->headers->set('Referrer-Policy', 'no-referrer');

        // Permissões de features do navegador
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
