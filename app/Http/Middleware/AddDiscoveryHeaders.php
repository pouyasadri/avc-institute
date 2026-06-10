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

        // Add discovery headers to the homepage and its localized versions
        if ($this->isHomepage($request)) {
            $links = [
                '</llms.txt>; rel="help"',
                '</llms.txt>; rel="describedby"',
                '</llms.txt>; rel="service-doc"',
                '</.well-known/api-catalog>; rel="api-catalog"',
                '</sitemap.xml>; rel="sitemap"',
            ];

            // RFC 8288: Link headers can be combined with commas
            $response->headers->set('Link', implode(', ', $links), false);
        }

        return $response;
    }

    /**
     * Determine if the request is for the homepage.
     */
    protected function isHomepage(Request $request): bool
    {
        return in_array($request->route()?->getName(), ['index', 'root.redirect']);
    }
}
