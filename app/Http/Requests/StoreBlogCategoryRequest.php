<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'string'],

            // Allow either flat fields or translations[] array
            'locale' => ['nullable', Rule::enum(Locale::class)],
            'name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],

            'translations' => ['sometimes', 'array'],
            'translations.*.locale' => ['required_with:translations', Rule::enum(Locale::class)],
            'translations.*.name' => ['nullable', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
        ];
    }
}
