<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $translations = $this->input('translations', []);
        if (is_array($translations)) {
            foreach ($translations as $locale => &$translation) {
                if (! empty($translation['body'])) {
                    $decoded = base64_decode($translation['body'], true);
                    if ($decoded !== false) {
                        $translation['body'] = $decoded;
                    }
                }
            }
            $this->merge(['translations' => $translations]);
        }
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'string'],
            'author_id' => ['nullable', 'exists:users,id'],
            'blog_main_image' => ['nullable', 'image', 'max:10240'], // 10MB
            'is_pinned' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],

            // Translations
            'translations' => ['required', 'array', 'min:1'],
            'translations.*.locale' => ['required', 'string', 'max:5', Rule::in(config('localization.supported_locales'))],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:500'],
            'translations.*.excerpt' => ['nullable', 'string', 'max:500'],
            'translations.*.body' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'translations.required' => 'At least one translation is required.',
            'translations.*.title.required' => 'Blog title is required for each language.',
            'translations.*.body.required' => 'Blog content is required for each language.',
            'blog_main_image.image' => __('validation.image', ['attribute' => 'main image']),
            'blog_main_image.max' => 'Main image must not exceed 10MB.',
        ];
    }
}
