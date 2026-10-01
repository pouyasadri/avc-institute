<?php

namespace App\Services\Seo;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Single source of truth for public absolute URLs used by the sitemap and IndexNow.
 */
class SiteUrlBuilder
{
    public function baseUrl(): string
    {
        return rtrim(config('app.url', 'https://applyvipconseil.com'), '/');
    }

    /**
     * @return array<int, string>
     */
    public function locales(): array
    {
        return array_keys(config('seo.locales', ['en' => [], 'fr' => [], 'fa' => []]));
    }

    /**
     * @return array<int, string>
     */
    public function cities(): array
    {
        return config('site_structure.cities', []);
    }

    /**
     * @return array<int, string>
     */
    public function universities(): array
    {
        return config('site_structure.universities', []);
    }

    /**
     * @return array<int, string>
     */
    public function services(): array
    {
        return config('site_structure.service_slugs', []);
    }

    /**
     * Locale-prefixed static page segments (must stay aligned with routes + sitemap).
     *
     * @return array<int, string>
     */
    public function staticPages(): array
    {
        return ['calculator', 'consult', 'contactUs', 'legal'];
    }

    public function encodeSlug(string $rawSlug): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $rawSlug)));
    }

    /**
     * Cached published blogs with translations (shared by sitemap + IndexNow).
     *
     * @return Collection<int, Blog>
     */
    public function publishedBlogs(): Collection
    {
        return Cache::remember('sitemap:blogs', 3600, function () {
            return Blog::published()->with('translations')->get();
        });
    }

    /**
     * Build public URLs for one blog post across locales, optionally including blog indexes.
     *
     * @return array<string>
     */
    public function blogPostUrls(Blog $blog, bool $includeIndexes = true): array
    {
        $blog->loadMissing('translations');

        $baseUrl = $this->baseUrl();
        $urls = [];

        foreach ($this->locales() as $locale) {
            $translation = $blog->getTranslation($locale);
            if ($translation && $translation->slug) {
                $urls[] = "{$baseUrl}/{$locale}/blog/{$this->encodeSlug($translation->slug)}";
            }
        }

        if ($includeIndexes) {
            foreach ($this->locales() as $locale) {
                $urls[] = "{$baseUrl}/{$locale}/blog";
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * All public absolute URLs that should be indexed / submitted to IndexNow.
     *
     * @return array<string>
     */
    public function buildAllAbsoluteUrls(): array
    {
        $baseUrl = $this->baseUrl();
        $locales = $this->locales();
        $urls = [];

        foreach ($locales as $locale) {
            $urls[] = "{$baseUrl}/{$locale}";
            $urls[] = "{$baseUrl}/{$locale}/blog";
            $urls[] = "{$baseUrl}/{$locale}/blog/categories";

            $urls[] = "{$baseUrl}/{$locale}/cities";
            foreach ($this->cities() as $city) {
                $urls[] = "{$baseUrl}/{$locale}/cities/{$city}";
            }

            $urls[] = "{$baseUrl}/{$locale}/universities";
            foreach ($this->universities() as $university) {
                $urls[] = "{$baseUrl}/{$locale}/universities/{$university}";
            }

            $urls[] = "{$baseUrl}/{$locale}/services";
            foreach ($this->services() as $service) {
                $urls[] = "{$baseUrl}/{$locale}/services/{$service}";
            }

            foreach ($this->staticPages() as $page) {
                $urls[] = "{$baseUrl}/{$locale}/{$page}";
            }
        }

        try {
            foreach ($this->publishedBlogs() as $blog) {
                foreach ($locales as $locale) {
                    $translation = $blog->getTranslation($locale);
                    if ($translation && $translation->slug) {
                        $urls[] = "{$baseUrl}/{$locale}/blog/{$this->encodeSlug($translation->slug)}";
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('SiteUrlBuilder: could not load blog posts — '.$e->getMessage());
        }

        return array_values(array_unique($urls));
    }
}
