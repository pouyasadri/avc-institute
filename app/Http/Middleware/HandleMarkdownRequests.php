<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleMarkdownRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the agent specifically requests markdown
        if ($request->header('Accept') === 'text/markdown' ||
            str_contains($request->header('Accept') ?? '', 'text/markdown')) {

            $response = $next($request);

            // Only attempt conversion for successful HTML responses
            if ($response->isSuccessful() && str_contains($response->headers->get('Content-Type') ?? '', 'text/html')) {
                return $this->convertToMarkdown($request, $response);
            }
        }

        return $next($request);
    }

    /**
     * Convert the HTML response to Markdown.
     */
    protected function convertToMarkdown(Request $request, Response $response): Response
    {
        $routeName = $request->route()?->getName();
        $viewName = "markdown.{$routeName}";

        // If we have a dedicated markdown view for this route, use it
        if ($routeName && View::exists($viewName)) {
            $markdownContent = View::make($viewName, $this->extractDataFromResponse($response))->render();
        } else {
            // Fallback: Use a generic template or a simple conversion
            $markdownContent = $this->generateFallbackMarkdown($response);
        }

        return response($markdownContent)
            ->header('Content-Type', 'text/markdown; charset=UTF-8')
            ->header('x-markdown-tokens', 'true');
    }

    /**
     * Extract data passed to the original view if possible.
     */
    protected function extractDataFromResponse(Response $response): array
    {
        if (property_exists($response, 'original') && $response->original instanceof \Illuminate\View\View) {
            return $response->original->getData();
        }

        return [];
    }

    /**
     * Generate a simple markdown fallback from HTML content.
     */
    protected function generateFallbackMarkdown(Response $response): string
    {
        $html = $response->getContent();

        // Very basic extraction of main content if we don't have a template
        // In a real scenario, you might use a library like league/html-to-markdown
        // For now, we'll provide a helpful message or a simple strip tags approach

        $title = preg_match('/<title>(.*?)<\/title>/', $html, $matches) ? $matches[1] : 'Apply VIP Conseil';

        return "# {$title}\n\n".
               'This page is currently optimized for HTML. Please visit https://applyvipconseil.com/llms.txt for a machine-readable summary of our services.';
    }
}
