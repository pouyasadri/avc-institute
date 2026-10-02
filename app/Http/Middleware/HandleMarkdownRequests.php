<?php

namespace App\Http\Middleware;

use App\Services\Discovery\HtmlToMarkdownConverter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class HandleMarkdownRequests
{
    public function __construct(
        protected HtmlToMarkdownConverter $htmlToMarkdown,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('Accept') === 'text/markdown' ||
            str_contains($request->header('Accept') ?? '', 'text/markdown')) {

            $response = $next($request);

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
        $viewName = $this->resolveMarkdownView($routeName);

        if ($viewName) {
            $data = array_merge(
                $this->extractDataFromResponse($response),
                $this->extraViewData($request, $routeName),
            );
            $markdownContent = View::make($viewName, $data)->render();
        } else {
            $markdownContent = $this->generateFallbackMarkdown($response);
        }

        return response($markdownContent)
            ->header('Content-Type', 'text/markdown; charset=UTF-8')
            ->header('x-markdown-tokens', 'true');
    }

    protected function resolveMarkdownView(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        if (View::exists("markdown.{$routeName}")) {
            return "markdown.{$routeName}";
        }

        if (str_starts_with($routeName, 'cities.') && $routeName !== 'cities.index' && View::exists('markdown.city')) {
            return 'markdown.city';
        }

        if (str_starts_with($routeName, 'universities.') && $routeName !== 'universities.index' && View::exists('markdown.university')) {
            return 'markdown.university';
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    protected function extraViewData(Request $request, ?string $routeName): array
    {
        $data = [
            'markdownCanonical' => url()->current(),
            'markdownLocale' => app()->getLocale(),
            'markdownOrgName' => config('seo.organization.name', 'A.V.C Institute'),
        ];

        if ($routeName && str_starts_with($routeName, 'cities.') && $routeName !== 'cities.index') {
            $data['citySlug'] = substr($routeName, strlen('cities.'));
        }

        if ($routeName && str_starts_with($routeName, 'universities.') && $routeName !== 'universities.index') {
            $data['universitySlug'] = substr($routeName, strlen('universities.'));
        }

        return $data;
    }

    /**
     * Extract data passed to the original view if possible.
     *
     * @return array<string, mixed>
     */
    protected function extractDataFromResponse(Response $response): array
    {
        if (property_exists($response, 'original') && $response->original instanceof \Illuminate\View\View) {
            return $response->original->getData();
        }

        return [];
    }

    /**
     * Generate markdown from HTML when no dedicated digest template exists.
     */
    protected function generateFallbackMarkdown(Response $response): string
    {
        $converted = $this->htmlToMarkdown->convert(
            (string) $response->getContent(),
            config('seo.organization.name', 'A.V.C Institute')
        );

        $title = $converted['title'];
        $canonical = $converted['canonical'] ?: url()->current();
        $body = $converted['body'];
        $llms = url('/llms.txt');

        if (mb_strlen($body) < 80) {
            return "# {$title}\n\n".
                "This page is currently optimized for HTML.\n\n".
                "## Cite this page\n{$canonical}\n\n".
                "## Machine-Readable Summary\n[llms.txt]({$llms})\n";
        }

        return "# {$title}\n\n".
            "{$body}\n\n".
            "## Cite this page\n{$canonical}\n\n".
            "## Related official sources\n".
            "- [Campus France](https://www.campusfrance.org/)\n".
            "- [France-Visas](https://france-visas.gouv.fr/)\n".
            "- [Service-Public.fr](https://www.service-public.fr/)\n\n".
            "## Machine-Readable Summary\n".
            "Prefer [llms.txt]({$llms}) for Persian-first routing across services.\n".
            'Organization: '.config('seo.organization.name', 'A.V.C Institute')."\n";
    }
}
