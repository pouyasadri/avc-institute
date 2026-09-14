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

    /**
     * Split an HTML body into cleaned narrative content and extracted FAQ items.
     * Strips the static/hardcoded FAQ accordion block and preceding heading so they can be rendered
     * via the centralized <x-sections.faq> component.
     *
     * @return array{content: string, faqs: array<int, array{question: string, answer: string}>, title: ?string}
     */
    public static function splitContentAndFaqs(?string $html): array
    {
        if (empty($html)) {
            return [
                'content' => '',
                'faqs' => [],
                'title' => null,
            ];
        }

        $accordionPos = strpos($html, 'id="faq-accordion"');
        if ($accordionPos === false) {
            $accordionPos = strpos($html, "id='faq-accordion'");
        }

        if ($accordionPos === false) {
            return [
                'content' => $html,
                'faqs' => [],
                'title' => null,
            ];
        }

        $faqs = self::extractFromHtml($html);

        // Find the beginning of the <div containing id="faq-accordion"
        $divStart = strrpos(substr($html, 0, $accordionPos), '<div');
        if ($divStart === false) {
            $divStart = $accordionPos;
        }

        $beforeDiv = substr($html, 0, $divStart);
        $title = null;
        $stripStart = $divStart;

        // Check if there is a heading directly before the accordion
        if (preg_match('/<h[234][^>]*>(.*?)<\/h[234]>\s*$/is', $beforeDiv, $hMatch)) {
            $title = trim(strip_tags(html_entity_decode($hMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            $hStart = strrpos($beforeDiv, '<h');
            if ($hStart !== false) {
                $stripStart = $hStart;
            }
        }

        $cleanContent = trim(substr($html, 0, $stripStart));

        return [
            'content' => $cleanContent,
            'faqs' => $faqs,
            'title' => $title,
        ];
    }
}
