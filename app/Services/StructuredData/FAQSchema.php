<?php

namespace App\Services\StructuredData;

class FAQSchema extends SchemaBuilder
{
    protected array $questions = [];

    public function __construct()
    {
        $this->setContext()->setType('FAQPage');
    }

    /**
     * Add a question and answer
     */
    public function addQuestion(string $question, string $answer): self
    {
        $this->questions[] = [
            '@type' => 'Question',
            'name' => $question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $answer,
            ],
        ];

        return $this;
    }

    /**
     * Add multiple questions from array
     */
    public function addQuestions(array $faqs): self
    {
        foreach ($faqs as $faq) {
            $this->addQuestion($faq['question'], $faq['answer']);
        }

        return $this;
    }

    /**
     * Build FAQ schema
     */
    public function build(): array
    {
        $this->add('mainEntity', $this->questions)
            ->add('inLanguage', app()->getLocale());

        return $this->data;
    }
}
