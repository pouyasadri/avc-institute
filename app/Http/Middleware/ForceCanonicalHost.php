<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to enforce canonical host and HTTPS.
 *
 * This is an application-level fallback when edge (proxy/CDN) rules are not available.
 * It redirects requests from legacy hosts (en.applyvipconseil.com, fr.applyvipconseil.com,
 * www.applyvipconseil.com, http) to the canonical https://applyvipconseil.com host.
 */
class ForceCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            return $next($request);
        }

        $appUrl = rtrim(parse_url(config('app.url', 'https://applyvipconseil.com'), PHP_URL_HOST) ?: 'applyvipconseil.com', '/');

        $host = $request->getHost();
        $scheme = $request->getScheme();

        // If host is already canonical and HTTPS, proceed
        if (strtolower($host) === strtolower($appUrl) && $scheme === 'https') {
            return $next($request);
        }

        // Normalize host by stripping legacy locale subdomains and leading www.
        $newHost = preg_replace('/^(?:www\.)?(?:en|fr)\./i', '', $host);
        $newHost = preg_replace('/^www\./i', '', $newHost);

        $newUrl = 'https://'.$newHost.$request->getRequestUri();

        return redirect()->to($newUrl, 301);
    }
}
