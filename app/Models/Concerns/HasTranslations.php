<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait HasTranslations
{
    /**
     * Resolve a translation for the given locale, preferring an eager-loaded
     * translations collection to avoid N+1 queries.
     */
    public function getTranslation(string $locale, bool $fallback = true): ?Model
    {
        /** @var Collection<int, Model> $collection */
        $collection = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        $translation = $collection->firstWhere('locale', $locale);

        if (! $translation && $fallback) {
            $fallbackLocale = config('app.fallback_locale', 'en');
            $translation = $collection->firstWhere('locale', $fallbackLocale);
        }

        return $translation;
    }
}
