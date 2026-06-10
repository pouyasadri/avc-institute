<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;

class SeoService
{
    protected array $meta = [];

    protected array $openGraph = [];

    protected array $twitter = [];

    protected array $hreflang = [];

    protected string $canonical = '';

    /**
     * Set page title
     */
    public function setTitle(string $title, bool $appendSuffix = true): self
    {
        $suffix = '';
        if ($appendSuffix) {
            $locale = app()->getLocale();
            $suffix = __('layout.seo.title_suffix');
            // Fallback if translation missing
            if ($suffix === 'layout.seo.title_suffix') {
                $suffix = config('seo.defaults.title_suffix', '');
            }
        }
        
        $this->meta['title'] = $title.$suffix;
        $this->openGraph['og:title'] = $title;
        $this->twitter['twitter:title'] = $title;

        return $this;
    }

    /**
     * Set meta description
     * Note: Persian/Arabic chars are wider in pixels, so we use a shorter limit.
     */
    public function setDescription(string $description): self
    {
        $locale = app()->getLocale();
        $descLimit = in_array($locale, ['fa', 'ar']) ? 130 : 155;
        $ogLimit = in_array($locale, ['fa', 'ar']) ? 170 : 200;

        $this->meta['description'] = $this->truncate($description, $descLimit);
        $this->openGraph['og:description'] = $this->truncate($description, $ogLimit);
        $this->twitter['twitter:description'] = $this->truncate($description, $ogLimit);

        return $this;
    }

    /**
     * Set keywords
     */
    public function setKeywords(string $keywords): self
    {
        $this->meta['keywords'] = $keywords;

        return $this;
    }

    /**
     * Set canonical URL
     */
    public function setCanonical(?string $url = null): self
    {
        $this->canonical = $this->enforcePrimaryDomain($url ?? URL::current());
        $this->openGraph['og:url'] = $this->canonical;

        return $this;
    }

    /**
     * Set image for social sharing
     */
    public function setImage(string $imageUrl, string $alt = '', string $mimeType = 'image/webp'): self
    {
        $fullImageUrl = $this->makeAbsoluteUrl($imageUrl);
        // Always serve canonical HTTPS URL for social scrapers
        $secureUrl = preg_replace('/^http:\/\//', 'https://', $fullImageUrl);

        $this->openGraph['og:image'] = $secureUrl;
        $this->openGraph['og:image:secure_url'] = $secureUrl;
        $this->openGraph['og:image:alt'] = $alt;
        $this->openGraph['og:image:type'] = $mimeType;
        $this->openGraph['og:image:width'] = config('seo.images.sizes.og.width', 1200);
        $this->openGraph['og:image:height'] = config('seo.images.sizes.og.height', 630);

        $this->twitter['twitter:image'] = $secureUrl;
        $this->twitter['twitter:image:alt'] = $alt;

        return $this;
    }

    /**
     * Set page type (article, website, product, etc.)
     */
    public function setType(string $type): self
    {
        $this->openGraph['og:type'] = $type;

        return $this;
    }

    /**
     * Set locale
     */
    public function setLocale(string $locale): self
    {
        $localeConfig = config("seo.locales.{$locale}", []);
        $ogLocale = $localeConfig['og_locale'] ?? 'en_US';

        $this->openGraph['og:locale'] = $ogLocale;
        $this->meta['language'] = $locale;

        return $this;
    }

    /**
     * Add alternate locales for Open Graph
     */
    public function addAlternateLocales(array $locales): self
    {
        $alternates = [];
        foreach ($locales as $locale) {
            $localeConfig = config("seo.locales.{$locale}", []);
            if (isset($localeConfig['og_locale'])) {
                $alternates[] = $localeConfig['og_locale'];
            }
        }

        if (! empty($alternates)) {
            $this->openGraph['og:locale:alternate'] = $alternates;
        }

        return $this;
    }

    /**
     * Add hreflang tags
     */
    public function addHreflang(string $locale, string $url): self
    {
        $this->hreflang[$locale] = $url;

        return $this;
    }

    /**
     * Set article-specific metadata
     */
    public function setArticle(array $data): self
    {
        $this->setType('article');

        if (isset($data['published_time'])) {
            $this->openGraph['article:published_time'] = $data['published_time'];
        }

        if (isset($data['modified_time'])) {
            $this->openGraph['article:modified_time'] = $data['modified_time'];
        }

        if (isset($data['author'])) {
            $this->openGraph['article:author'] = $data['author'];
        }

        if (isset($data['section'])) {
            $this->openGraph['article:section'] = $data['section'];
        }

        if (isset($data['tags']) && is_array($data['tags'])) {
            $this->openGraph['article:tag'] = $data['tags'];
        }

        return $this;
    }

    /**
     * Set Twitter Card type
     */
    public function setTwitterCard(string $type = 'summary_large_image'): self
    {
        $this->twitter['twitter:card'] = $type;

        return $this;
    }

    /**
     * Set Twitter site handle
     */
    public function setTwitterSite(string $handle): self
    {
        $this->twitter['twitter:site'] = $handle;

        return $this;
    }

    /**
     * Set robots meta tag
     */
    public function setRobots(string $robots): self
    {
        $this->meta['robots'] = $robots;

        return $this;
    }

    /**
     * Get all meta tags
     */
    public function getMeta(): array
    {
        return array_merge($this->getDefaults(), $this->meta);
    }

    /**
     * Get Open Graph tags
     */
    public function getOpenGraph(): array
    {
        $og = array_merge($this->getDefaultOpenGraph(), $this->openGraph);

        if (empty($og['og:image'])) {
            $og['og:image'] = $this->makeAbsoluteUrl(config('seo.images.default_og_image', config('seo.defaults.image')));
        }
        if (empty($og['og:url'])) {
            $og['og:url'] = $this->getCanonical();
        }
        if (empty($og['og:type'])) {
            $og['og:type'] = config('seo.defaults.type', 'website');
        }

        return $og;
    }

    /**
     * Get Twitter Card tags
     */
    public function getTwitter(): array
    {
        $twitter = array_merge($this->getDefaultTwitter(), $this->twitter);

        if (empty($twitter['twitter:card'])) {
            $twitter['twitter:card'] = config('seo.social.twitter.card', 'summary_large_image');
        }
        if (empty($twitter['twitter:image'])) {
            $twitter['twitter:image'] = $this->makeAbsoluteUrl(config('seo.images.default_twitter_image', config('seo.defaults.image')));
        }

        return $twitter;
    }

    /**
     * Get hreflang tags
     */
    public function getHreflang(): array
    {
        return $this->hreflang;
    }

    /**
     * Get canonical URL
     */
    public function getCanonical(): string
    {
        return $this->canonical ?: $this->enforcePrimaryDomain(URL::current());
    }

    /**
     * Generate meta tags for a blog post
     */
    public function forBlogPost($blog, string $locale): self
    {
        $translation = $blog->getTranslation($locale);

        if (! $translation) {
            return $this;
        }

        $this->setTitle($translation->title)
            ->setDescription($translation->excerpt ?? strip_tags($translation->body))
            ->setKeywords($translation->title)
            ->setLocale($locale)
            ->setType('article')
            ->setTwitterCard('summary_large_image');

        if ($blog->main_image) {
            $this->setImage(
                asset('storage/'.$blog->main_image),
                $translation->title
            );
        }

        $this->setArticle([
            'published_time' => $blog->published_at?->toIso8601String() ?? $blog->created_at->toIso8601String(),
            'modified_time' => $blog->updated_at->toIso8601String(),
            'author' => $blog->author?->name ?? config('seo.defaults.author'),
            'section' => $blog->category?->getTranslation($locale)?->name ?? 'Blog',
        ]);

        return $this;
    }

    /**
     * Generate meta tags for a property
     */
    public function forProperty($property, string $locale): self
    {
        $translation = $property->getTranslation($locale);

        if (! $translation) {
            return $this;
        }

        $title = $translation->name ?? 'Property';

        // Locale-aware fallback — avoids English text on Persian/French pages
        if (! empty(strip_tags($translation->description ?? ''))) {
            $description = strip_tags($translation->description);
        } else {
            $description = __('properties.fallback_description', [
                'city' => $property->city ?? '',
                'rooms' => $property->rooms ?? '',
                'price' => number_format($property->price ?? 0, 0),
            ]);
            // Final safety fallback if translation key missing
            if ($description === 'properties.fallback_description') {
                $description = "Property in {$property->city}. {$property->rooms} rooms, \u20ac".number_format($property->price, 0);
            }
        }

        $this->setTitle($title)
            ->setDescription($description)
            ->setLocale($locale)
            ->setType('product')
            ->setTwitterCard('summary_large_image');

        if ($property->main_image) {
            $this->setImage(
                asset('storage/'.$property->main_image),
                $title
            );
        }

        return $this;
    }

    /**
     * Generate meta tags for homepage
     */
    public function forHomepage(string $locale): self
    {
        // Use localized values from resources/lang/{locale}/index.php
        $title = __('index.meta.title');
        $description = __('index.meta.description');
        $keywords = __('index.meta.keywords');

        // Fallback if translation key is returned (meaning translation missing)
        if ($title === 'index.meta.title') {
            $title = config('seo.defaults.title');
        }
        if ($description === 'index.meta.description') {
            $description = config('seo.defaults.description');
        }

        $this->setTitle($title, false)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setLocale($locale)
            ->setType('website')
            ->setTwitterCard('summary_large_image');

        // Add explicit Open Graph localization
        if (request()->routeIs('index')) {
            $this->openGraph['og:site_name'] = __('index.meta.og.site_name');
            $this->openGraph['og:title'] = __('index.meta.og.title');
            $this->openGraph['og:description'] = __('index.meta.og.description');

            // Allow overriding defaults if localization has specific OG keys
            if (__('index.meta.og.type') !== 'index.meta.og.type') {
                $this->openGraph['og:type'] = __('index.meta.og.type');
            }
            if (__('index.meta.twitter.title') !== 'index.meta.twitter.title') {
                $this->twitter['twitter:title'] = __('index.meta.twitter.title');
                $this->twitter['twitter:description'] = __('index.meta.twitter.description');
            }
        }

        return $this;
    }

    /**
     * Get default meta tags
     */
    protected function getDefaults(): array
    {
        return [
            'author' => config('seo.defaults.author'),
            'robots' => config('seo.robots.default'),
        ];
    }

    /**
     * Get default Open Graph tags
     */
    protected function getDefaultOpenGraph(): array
    {
        return [
            'og:site_name' => config('seo.defaults.title'),
            'og:locale' => config('seo.defaults.locale'),
        ];
    }

    /**
     * Get default Twitter tags
     */
    protected function getDefaultTwitter(): array
    {
        return [
            'twitter:site' => config('seo.social.twitter.site'),
            'twitter:creator' => config('seo.social.twitter.creator'),
        ];
    }

    /**
     * Truncate text to specified length, breaking at word boundary.
     * Avoids cutting mid-word which looks unprofessional in search snippets.
     */
    protected function truncate(string $text, int $length): string
    {
        $text = strip_tags($text);
        // Collapse whitespace (tabs, newlines) to single spaces
        $text = preg_replace('/\s+/', ' ', trim($text));

        if (mb_strlen($text) <= $length) {
            return $text;
        }

        // Find the last space within the allowed length, then trim
        $truncated = mb_substr($text, 0, $length - 1);
        $lastSpace = mb_strrpos($truncated, ' ');

        if ($lastSpace !== false) {
            $truncated = mb_substr($truncated, 0, $lastSpace);
        }

        return rtrim($truncated, '.,;:').'…';
    }

    /**
     * Make URL absolute
     */
    protected function makeAbsoluteUrl(string $url): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return url($url);
    }

    /**
     * Enforce the primary domain from config('app.url') on a given URL
     */
    protected function enforcePrimaryDomain(string $url): string
    {
        $appUrl = config('app.url');
        if (! $appUrl) {
            return $url;
        }

        $parsedUrl = parse_url($url);
        $parsedAppUrl = parse_url($appUrl);

        $scheme = $parsedAppUrl['scheme'] ?? ($parsedUrl['scheme'] ?? 'https');
        $host = $parsedAppUrl['host'] ?? ($parsedUrl['host'] ?? '');
        $path = $parsedUrl['path'] ?? '';

        return "{$scheme}://{$host}{$path}";
    }
}
