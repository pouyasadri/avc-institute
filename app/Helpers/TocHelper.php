<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class TocHelper
{
    /**
     * Parses HTML content, injects missing IDs into H2 and H3 tags, and generates a Table of Contents.
     *
     * @return array{content: string, toc: array}
     */
    public static function generate(string $content): array
    {
        $toc = [];
        $pattern = '/<(h[23])([^>]*)>(.*?)<\/\1>/is';

        $contentWithIds = preg_replace_callback($pattern, function ($matches) use (&$toc) {
            $tag = $matches[1];
            $attributes = $matches[2];
            $innerContent = $matches[3];
            $text = strip_tags($innerContent);

            // Extract or generate ID
            if (preg_match('/id=[\'"]([^\'"]+)[\'"]/i', $attributes, $idMatches)) {
                $id = $idMatches[1];
            } else {
                $id = Str::slug($text);
                if (empty($id)) {
                    $id = 'section-'.uniqid();
                }
                $attributes .= ' id="'.$id.'"';
            }

            // Add to TOC array if it's not just empty space
            if (! empty(trim($text))) {
                $toc[] = [
                    'level' => intval(substr($tag, 1)),
                    'title' => trim($text),
                    'id' => $id,
                ];
            }

            return "<{$tag}{$attributes}>{$innerContent}</{$tag}>";
        }, $content);

        return [
            'content' => $contentWithIds,
            'toc' => $toc,
        ];
    }
}
