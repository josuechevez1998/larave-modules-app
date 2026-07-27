<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Origins allowed only while Vite HMR runs in local development.
     *
     * @var list<string>
     */
    private const LOCAL_VITE_ORIGINS = [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'ws://localhost:5173',
        'ws://127.0.0.1:5173',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);
        $response->headers->set('X-Content-Type-Options', 'nosniff', false);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()', false);
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin', false);

        $viteOrigins = $this->localViteOrigins();

        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; "
            ."base-uri 'self'; "
            ."form-action 'self'; "
            ."frame-ancestors 'self'; "
            ."object-src 'none'; "
            ."img-src 'self' data: blob:; "
            ."font-src 'self' data: https://fonts.bunny.net{$viteOrigins}; "
            ."style-src 'self' 'unsafe-inline' https://fonts.bunny.net{$viteOrigins}; "
            ."script-src 'self' 'unsafe-inline' 'unsafe-eval'{$viteOrigins}; "
            ."connect-src 'self' ws: wss: https://fonts.bunny.net{$viteOrigins};",
            false
        );

        if ($request->secure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains',
                false
            );
        }

        return $response;
    }

    private function localViteOrigins(): string
    {
        if (! app()->environment('local')) {
            return '';
        }

        return ' '.implode(' ', self::LOCAL_VITE_ORIGINS);
    }
}
