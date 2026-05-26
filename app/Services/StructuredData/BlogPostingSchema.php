<?php

namespace App\Services\StructuredData;

class BlogPostingSchema extends SchemaBuilder
{
    protected $blog;

    protected $translation;

    protected string $locale;

    public function __construct($blog, string $locale)
    {
        $this->blog = $blog;
        $this->locale = $locale;
        $this->translation = $blog->getTranslation($locale);

        $this->setContext()->setType('BlogPosting');
    }

    /**
     * Build blog posting schema
     */
    public function build(): array
    {
        if (! $this->translation) {
            return [];
        }

        $this->add('headline', $this->translation->title)
            ->add('description', $this->translation->excerpt ?? strip_tags(substr($this->translation->body, 0, 200)))
            ->add('image', $this->buildImage())
            ->add('author', $this->buildAuthor())
            ->add('publisher', $this->buildPublisher())
            ->add('datePublished', $this->blog->published_at?->toIso8601String() ?? $this->blog->created_at->toIso8601String())
            ->add('dateModified', $this->blog->updated_at->toIso8601String())
            ->add('mainEntityOfPage', [
                '@type' => 'WebPage',
                '@id' => $this->url(route('blog.show', ['locale' => $this->locale, 'blog' => $this->blog->id])),
            ]);

        // Add article body excerpt for LLMs — use mb_substr to preserve Persian codepoints
        if ($this->translation->body) {
            $bodyText = strip_tags($this->translation->body);
            $bodyText = preg_replace('/\s+/', ' ', trim($bodyText));
            $this->add('articleBody', mb_substr($bodyText, 0, 1500));
        }

        // Add word count — for RTL/Persian text use mb_str_split for accuracy
        if ($this->translation->body) {
            $plainText = strip_tags($this->translation->body);
            // str_word_count is unreliable for Persian; count whitespace-delimited tokens instead
            $wordCount = count(preg_split('/\s+/', trim($plainText), -1, PREG_SPLIT_NO_EMPTY));
            $this->add('wordCount', $wordCount);
        }

        // Add speakable specification for AI assistants and Google voice search
        $this->add('speakable', [
            '@type' => 'SpeakableSpecification',
            'cssSelector' => ['.blog-details-desc h1', '.blog-details-desc h2', '.blog-details-desc p'],
        ]);

        // Add category/section
        if ($this->blog->category) {
            $categoryTranslation = $this->blog->category->getTranslation($this->locale);
            if ($categoryTranslation) {
                $this->add('articleSection', $categoryTranslation->name);
            }
        }

        // Add language — critical for LLM/search engine locale matching
        $this->add('inLanguage', $this->locale);

        return $this->data;
    }

    /**
     * Build image object
     */
    protected function buildImage(): ?array
    {
        if (! $this->blog->main_image) {
            return null;
        }

        return [
            '@type' => 'ImageObject',
            'url' => $this->asset('storage/'.$this->blog->main_image),
            'width' => 1200,
            'height' => 630,
        ];
    }

    /**
     * Build author object
     */
    protected function buildAuthor(): array
    {
        return [
            '@type' => 'Person',
            'name' => $this->blog->author?->name ?? config('seo.defaults.author'),
        ];
    }

    /**
     * Build publisher object
     */
    protected function buildPublisher(): array
    {
        $orgId = rtrim(config('app.url'), '/').'/'.'#organization';

        return ['@id' => $orgId];
    }
}
