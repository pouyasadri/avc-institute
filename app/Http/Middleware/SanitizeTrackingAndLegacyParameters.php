<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeTrackingAndLegacyParameters
{
    /**
     * Legacy internal debugging or dirty query parameters that should not be indexed.
     * Found in GSC audit (e.g. ?view=university.sciences-po&status=200).
     */
    protected array $dirtyParameters = [
        'view',
        'status',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only sanitize safe GET/HEAD requests
        if (! $request->isMethodSafe()) {
            return $next($request);
        }

        $query = $request->query();

        if (empty($query)) {
            return $next($request);
        }

        $dirtyFound = false;
        foreach ($this->dirtyParameters as $param) {
            if (array_key_exists($param, $query)) {
                unset($query[$param]);
                $dirtyFound = true;
            }
        }

        if ($dirtyFound) {
            $cleanUrl = $request->url();
            if (! empty($query)) {
                $cleanUrl .= '?'.http_build_query($query);
            }

            return redirect($cleanUrl, 301);
        }

        return $next($request);
    }
}
