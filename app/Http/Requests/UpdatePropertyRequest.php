<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'rooms' => ['nullable', 'integer', 'min:0', 'max:100'],
            'garages' => ['nullable', 'integer', 'min:0', 'max:20'],
            'type' => ['sometimes', 'required', Rule::enum(PropertyType::class)],
            'status' => ['nullable', Rule::enum(PropertyStatus::class)],
            'address_line' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'required', 'string', 'max:12'],
            'city' => ['sometimes', 'required', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:2'],
            'main_image' => ['nullable', 'image', 'max:10240'],
            'published_at' => ['nullable', 'date'],

            // Translations
            'translations' => ['sometimes', 'array'],
            'translations.*.locale' => ['required_with:translations', Rule::enum(Locale::class)],
            'translations.*.name' => ['required_with:translations', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.description' => ['required_with:translations', 'string'],

            // Gallery images
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'max:10240'],

            // Delete specific gallery images
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => __('validation.required', ['attribute' => 'price']),
            'type.in' => __('validation.in', ['attribute' => 'property type']),
            'main_image.image' => __('validation.image', ['attribute' => 'main image']),
            'main_image.max' => 'Main image must not exceed 10MB.',
        ];
    }
}
