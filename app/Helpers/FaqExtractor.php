<?php

namespace App\Helpers;

class FaqExtractor
{
    /**
     * Extract Question & Answer pairs from HTML content for Schema.org FAQPage generation.
     *
     * Handles the standard FAQ accordion patterns used across blog articles:
     * - Question: <span class="faq-question ...">...</span>
     * - Answer:   <div class="faq-answer ...">...</div>
     *
     * @return array<int, array{question: string, answer: string}>
     */
    public static function extractFromHtml(?string $html): array
    {
        if (empty($html)) {
            return [];
        }

        // Fast bail-out if no FAQ markup exists
        if (! str_contains($html, 'faq-question') && ! str_contains($html, 'faq-accordion')) {
            return [];
        }

        $faqs = [];

        preg_match_all('/<span[^>]*class=[\'"][^\'"]*faq-question[^\'"]*[\'"][^>]*>(.*?)<\/span>/is', $html, $questionMatches);
        preg_match_all('/<div[^>]*class=[\'"][^\'"]*faq-answer[^\'"]*[\'"][^>]*>(.*?)<\/div>/is', $html, $answerMatches);

        $questions = $questionMatches[1] ?? [];
        $answers = $answerMatches[1] ?? [];

        $count = min(count($questions), count($answers));
        for ($i = 0; $i < $count; $i++) {
            $q = trim(strip_tags(html_entity_decode($questions[$i], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            $a = trim(strip_tags(html_entity_decode($answers[$i], ENT_QUOTES | ENT_HTML5, 'UTF-8')));

            // Normalize internal whitespace and tabs
            $q = preg_replace('/\s+/', ' ', $q);
            $a = preg_replace('/\s+/', ' ', $a);

            if (! empty($q) && ! empty($a)) {
                $faqs[] = [
                    'question' => $q,
                    'answer' => $a,
                ];
            }
        }

        return $faqs;
    }
}
