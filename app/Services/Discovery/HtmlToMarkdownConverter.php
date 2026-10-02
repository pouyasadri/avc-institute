<?php

namespace App\Services\Discovery;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

/**
 * Best-effort HTML → Markdown converter for agent Accept: text/markdown fallbacks.
 */
class HtmlToMarkdownConverter
{
    public function convert(string $html, string $fallbackTitle = 'A.V.C Institute'): array
    {
        $title = $fallbackTitle;
        if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
            $title = html_entity_decode(trim(strip_tags($matches[1])), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $canonical = null;
        if (preg_match('/<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']+)["\']/i', $html, $matches)) {
            $canonical = $matches[1];
        } elseif (preg_match('/<link[^>]+href=["\']([^"\']+)["\'][^>]+rel=["\']canonical["\']/i', $html, $matches)) {
            $canonical = $matches[1];
        }

        $mainHtml = $this->extractMainHtml($html);
        $markdownBody = $this->htmlFragmentToMarkdown($mainHtml);

        return [
            'title' => $title,
            'canonical' => $canonical,
            'body' => trim($markdownBody),
        ];
    }

    protected function extractMainHtml(string $html): string
    {
        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument;
        $loaded = $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return strip_tags($html);
        }

        $xpath = new DOMXPath($dom);
        foreach (['//script', '//style', '//noscript', '//nav', '//footer', '//header', '//aside'] as $query) {
            foreach ($xpath->query($query) ?: [] as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        $candidates = [
            '//main',
            '//*[@role="main"]',
            '//article',
            '//*[contains(@class,"service-details")]',
            '//*[contains(@class,"city-content")]',
            '//*[contains(@class,"university")]',
            '//*[contains(@class,"blog-details")]',
            '//body',
        ];

        foreach ($candidates as $query) {
            $nodes = $xpath->query($query);
            if ($nodes && $nodes->length > 0) {
                $htmlBits = [];
                foreach ($nodes as $node) {
                    $htmlBits[] = $dom->saveHTML($node) ?: '';
                }

                return implode("\n", $htmlBits);
            }
        }

        return strip_tags($html);
    }

    protected function htmlFragmentToMarkdown(string $html): string
    {
        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument;
        $dom->loadHTML('<?xml encoding="UTF-8"><div id="md-root">'.$html.'</div>', LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $dom->getElementById('md-root');
        if (! $root) {
            return trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        $lines = [];
        $this->walk($root, $lines);

        $text = preg_replace("/\n{3,}/", "\n\n", implode("\n", $lines)) ?? '';

        return trim($text);
    }

    /**
     * @param  array<int, string>  $lines
     */
    protected function walk(DOMNode $node, array &$lines, int $depth = 0): void
    {
        if ($node instanceof DOMElement) {
            $tag = strtolower($node->tagName);

            if (in_array($tag, ['h1', 'h2', 'h3', 'h4'], true)) {
                $level = (int) $tag[1];
                $text = $this->inlineText($node);
                if ($text !== '') {
                    $lines[] = '';
                    $lines[] = str_repeat('#', $level).' '.$text;
                    $lines[] = '';
                }

                return;
            }

            if ($tag === 'p') {
                $text = $this->inlineText($node);
                if ($text !== '') {
                    $lines[] = $text;
                    $lines[] = '';
                }

                return;
            }

            if ($tag === 'li') {
                $text = $this->inlineText($node);
                if ($text !== '') {
                    $lines[] = '- '.$text;
                }

                return;
            }

            if (in_array($tag, ['ul', 'ol'], true)) {
                foreach ($node->childNodes as $child) {
                    $this->walk($child, $lines, $depth + 1);
                }
                $lines[] = '';

                return;
            }

            if ($tag === 'br') {
                $lines[] = '';

                return;
            }

            if (in_array($tag, ['a', 'strong', 'em', 'span', 'div', 'section', 'article', 'main'], true) || $depth === 0) {
                foreach ($node->childNodes as $child) {
                    $this->walk($child, $lines, $depth + 1);
                }

                return;
            }

            $text = $this->inlineText($node);
            if ($text !== '') {
                $lines[] = $text;
            }

            return;
        }

        if ($node->nodeType === XML_TEXT_NODE) {
            $text = trim(preg_replace('/\s+/u', ' ', $node->nodeValue ?? '') ?? '');
            if ($text !== '') {
                $lines[] = $text;
            }
        }
    }

    protected function inlineText(DOMNode $node): string
    {
        $text = $node->textContent ?? '';
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? '';

        return trim($text);
    }
}
