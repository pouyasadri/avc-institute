<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Inject GDPR-relevant and general security HTTP response headers.
     *
     * Implements Article 32 GDPR technical measures:
     *  - Content-Security-Policy  — restricts resource origins
     *  - Permissions-Policy       — disables unused browser capabilities
     *  - Referrer-Policy          — limits referrer data leakage
     *  - X-Content-Type-Options   — prevents MIME sniffing
     *  - X-Frame-Options          — prevents clickjacking
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Remove server fingerprinting headers (both PHP FastCGI and response bag)
        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        // Content-Security-Policy
        // - Microsoft Clarity is allowed only from 'self' context (loaded after consent)
        // - Bootstrap CDN icons are loaded via the bundled build, so 'self' covers them
        $response->headers->set(
            'Content-Security-Policy',
            implode('; ', [
                "default-src 'self'",
                "script-src 'self' https://www.clarity.ms https://c.bing.com https://cdn.tiny.cloud https://www.amcharts.com 'unsafe-inline' 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
                "font-src 'self' https://fonts.gstatic.com data:",
                "img-src 'self' data: https://www.clarity.ms https://c.bing.com https://www.amcharts.com",
                "connect-src 'self' https://www.clarity.ms https://c.bing.com https://cdn.tiny.cloud",
                "frame-src 'self' https://www.google.com",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                'upgrade-insecure-requests',
            ])
        );

        // Permissions Policy — disable capabilities we don't use
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=()'
        );

        // Referrer Policy — don't leak full URL to third-party analytics
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Standard security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // HSTS — tell browsers to always use HTTPS (1 year)
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}
