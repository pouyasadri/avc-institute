<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckRedirects
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $path = $request->getPathInfo();

        // Check for legacy subdomain structure and redirect before locale detection
        // Handles all variants:
        //   en.applyvipconseil.com     -> https://applyvipconseil.com/en/...
        //   fr.applyvipconseil.com     -> https://applyvipconseil.com/fr/...
        //   www.en.applyvipconseil.com -> https://applyvipconseil.com/en/...
        //   www.fr.applyvipconseil.com -> https://applyvipconseil.com/fr/...
        if (preg_match('/^(?:www\.)?(en|fr)\./', $host, $matches)) {
            $locale = $matches[1];

            // Strip the locale subdomain (and optional www.) to get the bare canonical host
            $newHost = preg_replace('/^(?:www\.)?(en|fr)\./', '', $host);
            // Ensure no stray www remains on the canonical host
            $newHost = preg_replace('/^www\./', '', $newHost);

            // Strip any existing leading locale segment from the path to avoid double-prefixing
            // e.g. /fa/cities/nice stays as /fa/cities/nice (we keep original locale in path)
            // e.g. /en/blog stays as /blog (locale subdomain takes precedence)
            $pathWithoutLeadingLocale = preg_replace('/^\/(en|fr|fa)(?=\/|$)/', '', $path);
            $newPath = '/' . $locale . ($pathWithoutLeadingLocale ?: '');

            $newUrl = 'https://' . $newHost . $newPath;

            // Preserve query string if present
            if ($request->getQueryString()) {
                $newUrl .= '?' . $request->getQueryString();
            }

            return redirect($newUrl, 301);
        }

        // Check database for exact URL matches (for other legacy URLs)
        $fullUrl = $request->fullUrl();

        $redirect = Cache::remember("redirect-{$fullUrl}", 3600, function () use ($fullUrl) {
            return Redirect::where('from_url', $fullUrl)->first();
        });

        if ($redirect) {
            return redirect($redirect->to_url, $redirect->http_code);
        }

        return $next($request);
    }
}
