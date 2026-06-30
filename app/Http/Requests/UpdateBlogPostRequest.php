<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogPostRequest extends FormRequest
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
            'blog_main_image' => ['nullable', 'image', 'max:10240'],
            'is_pinned' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],

            // Translations
            'translations' => ['sometimes', 'array'],
            'translations.*.locale' => ['required_with:translations', 'string', 'max:5', Rule::in(config('localization.supported_locales'))],
            'translations.*.title' => ['required_with:translations', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:500'],
            'translations.*.excerpt' => ['nullable', 'string', 'max:500'],
            'translations.*.body' => ['required_with:translations', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'blog_main_image.image' => __('validation.image', ['attribute' => 'main image']),
            'blog_main_image.max' => 'Main image must not exceed 10MB.',
        ];
    }
}
