<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddDiscoveryHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldAddDiscoveryHeaders($request)) {
            $links = [
                '</llms.txt>; rel="help"',
                '</llms.txt>; rel="describedby"',
                '</llms.txt>; rel="service-doc"',
                '</llms-full.txt>; rel="service-doc"',
                '</.well-known/agents-index.json>; rel="index"',
                '</.well-known/api-catalog>; rel="api-catalog"',
                '</sitemap.xml>; rel="sitemap"',
            ];

            // RFC 8288: Link headers can be combined with commas
            $response->headers->set('Link', implode(', ', $links), false);
        }

        return $response;
    }

    /**
     * Homepage plus high-value public content surfaces for AI crawlers.
     */
    protected function shouldAddDiscoveryHeaders(Request $request): bool
    {
        $routeName = $request->route()?->getName();

        if (! $routeName) {
            return false;
        }

        if (in_array($routeName, ['index', 'root.redirect', 'calculator'], true)) {
            return true;
        }

        foreach (['services.', 'cities.', 'universities.', 'blog.'] as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
