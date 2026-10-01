<?php

namespace App\Services\Blog;

use App\Models\BlogPostTranslation;

/**
 * Resolve blog post translations from raw URL slug variants
 * (percent-encoding, Persian/Arabic digits, Arabic character forms).
 */
class BlogSlugResolver
{
    /**
     * @return array<int, string>
     */
    public function candidates(string $rawSlug): array
    {
        $decoded = urldecode($rawSlug);
        $rawDecoded = rawurldecode($rawSlug);

        $faDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arDigits = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $toEnDigits = fn (string $s): string => str_replace(
            array_merge($faDigits, $arDigits),
            array_merge($enDigits, $enDigits),
            $s
        );
        $toFaDigits = fn (string $s): string => str_replace($enDigits, $faDigits, $s);
        $normalizeChars = fn (string $s): string => str_replace(['ي', 'ك', 'ة', 'ى'], ['ی', 'ک', 'ه', 'ی'], $s);

        return array_values(array_unique(array_filter([
            $rawSlug,
            $decoded,
            $rawDecoded,
            $toEnDigits($decoded),
            $toFaDigits($decoded),
            $normalizeChars($decoded),
            $normalizeChars($toEnDigits($decoded)),
            $normalizeChars($toFaDigits($decoded)),
        ])));
    }

    public function resolve(string $locale, string $rawSlug): ?BlogPostTranslation
    {
        $candidates = $this->candidates($rawSlug);

        if ($candidates === []) {
            return null;
        }

        return BlogPostTranslation::query()
            ->whereIn('slug', $candidates)
            ->where('locale', $locale)
            ->first();
    }

    /**
     * Whether the requested slug should 301 to the canonical stored slug.
     */
    public function needsCanonicalRedirect(string $requestedSlug, string $canonicalSlug): bool
    {
        return $requestedSlug !== $canonicalSlug
            && $requestedSlug !== rawurlencode($canonicalSlug);
    }
}
