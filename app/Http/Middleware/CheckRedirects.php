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
     * @param  Closure(Request): (Response)  $next
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
            $newPath = '/'.$locale.($pathWithoutLeadingLocale ?: '');

            $newUrl = 'https://'.$newHost.$newPath;

            // Preserve query string if present
            if ($request->getQueryString()) {
                $newUrl .= '?'.$request->getQueryString();
            }

            return redirect($newUrl, 301);
        }

        // Check database for redirect matches (for other legacy URLs).
        // Cache keyed by path only (not full URL with query string) to prevent
        // unbounded cache growth from bots with random query params.
        // A value of false means "no redirect exists for this path" (null hit cached).
        $fullUrl = $request->fullUrl();
        $redirectData = Cache::remember("redirect:{$path}", 3600, function () use ($path, $fullUrl) {
            // Match against path OR full URL (supports legacy records stored with full URLs)
            $redirect = Redirect::where('from_url', $path)
                ->orWhere('from_url', $fullUrl)
                ->first();

            if ($redirect) {
                // Store only scalars — not the full Eloquent model object
                return ['to' => $redirect->to_url, 'code' => $redirect->http_code];
            }

            // Cache the miss so we skip the DB on repeated unknown paths
            return false;
        });

        if ($redirectData !== false) {
            return redirect($redirectData['to'], $redirectData['code']);
        }

        return $next($request);
    }
}
