<?php

namespace App\Services\Seo;

use App\Services\SeoService;
use Illuminate\Support\Facades\Storage;

/**
 * Page-level SEO profiles applied onto SeoService.
 * Keeps SeoService focused on fluent meta primitives.
 *
 * @mixin SeoService
 */
trait PageSeoProfiles
{
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
                Storage::url($blog->main_image),
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

        if (! empty(strip_tags($translation->description ?? ''))) {
            $description = strip_tags($translation->description);
        } else {
            $description = __('properties.fallback_description', [
                'city' => $property->city ?? '',
                'rooms' => $property->rooms ?? '',
                'price' => number_format($property->price ?? 0, 0),
            ]);
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
                Storage::url($property->main_image),
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
        $title = __('index.meta.title');
        $description = __('index.meta.description');
        $keywords = __('index.meta.keywords');

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

        if (request()->routeIs('index')) {
            $this->openGraph['og:site_name'] = __('index.meta.og.site_name');
            $this->openGraph['og:title'] = __('index.meta.og.title');
            $this->openGraph['og:description'] = __('index.meta.og.description');

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
     * Generate meta tags for blogs index page
     */
    public function forBlogIndex(string $locale): self
    {
        $title = __('blog/index.meta.title');
        if ($title === 'blog/index.meta.title') {
            $title = __('blog/index.title');
        }

        $description = __('blog/index.meta.description');
        if ($description === 'blog/index.meta.description') {
            $description = __('blog/index.description');
        }

        $keywords = __('blog/index.meta.keywords');
        if ($keywords === 'blog/index.meta.keywords') {
            $keywords = __('blog/index.keywords');
        }

        if ($title === 'blog/index.title') {
            $title = config('seo.defaults.title');
        }
        if ($description === 'blog/index.description') {
            $description = config('seo.defaults.description');
        }

        $this->setTitle($title, false)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setLocale($locale)
            ->setType('website')
            ->setTwitterCard('summary_large_image');

        $ogSiteName = __('blog/index.meta.og.site_name');
        if ($ogSiteName !== 'blog/index.meta.og.site_name') {
            $this->openGraph['og:site_name'] = $ogSiteName;
        }

        $ogTitle = __('blog/index.meta.og.title');
        if ($ogTitle !== 'blog/index.meta.og.title') {
            $this->openGraph['og:title'] = $ogTitle;
        }

        $ogDesc = __('blog/index.meta.og.description');
        if ($ogDesc !== 'blog/index.meta.og.description') {
            $this->openGraph['og:description'] = $ogDesc;
        }

        $twitterTitle = __('blog/index.meta.twitter.title');
        if ($twitterTitle !== 'blog/index.meta.twitter.title') {
            $this->twitter['twitter:title'] = $twitterTitle;
        }

        $twitterDesc = __('blog/index.meta.twitter.description');
        if ($twitterDesc !== 'blog/index.meta.twitter.description') {
            $this->twitter['twitter:description'] = $twitterDesc;
        }

        return $this;
    }

    /**
     * Generate meta tags for the public blog categories listing (SEO).
     */
    public function forBlogCategories(string $locale): self
    {
        $titles = [
            'fa' => 'دسته‌بندی‌های وبلاگ | مشاوره مهاجرت و تحصیل فرانسه',
            'fr' => 'Catégories du blog | Immigration et études en France',
            'en' => 'Blog Categories | France Immigration & Study Advice',
        ];

        $descriptions = [
            'fa' => 'مرور دسته‌بندی‌های مقالات AVC درباره مهاجرت، ویزا، تحصیل و زندگی در فرانسه.',
            'fr' => 'Parcourez les catégories d’articles AVC sur l’immigration, les visas, les études et la vie en France.',
            'en' => 'Browse AVC article categories on immigration, visas, studying, and life in France.',
        ];

        $this->setTitle($titles[$locale] ?? $titles['en'], false)
            ->setDescription($descriptions[$locale] ?? $descriptions['en'])
            ->setLocale($locale)
            ->setType('website')
            ->setTwitterCard('summary');

        return $this;
    }
}
