<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'string'],
            'blog_main_image' => ['required', 'image', 'max:5120'], // max 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'blog_main_image.required' => __('validation.required', ['attribute' => 'main image']),
            'blog_main_image.image' => __('validation.image', ['attribute' => 'main image']),
        ];
    }
}
