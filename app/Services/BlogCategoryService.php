<?php

namespace App\Services;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Collection;

class BlogCategoryService
{
    public function getAllCategories(): Collection
    {
        return BlogCategory::with(['translations', 'parent.translations'])
            ->withCount('posts')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function storeCategory(array $data): BlogCategory
    {
        $category = BlogCategory::create([
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        // Support creating one or many translations
        if (! empty($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $t) {
                if (empty($t['locale'])) {
                    continue;
                }
                $category->translations()->create([
                    'locale' => $t['locale'],
                    'name' => $t['name'] ?? '',
                    'slug' => $t['slug'] ?? '',
                ]);
            }
        } elseif (! empty($data['locale']) && (! empty($data['name']) || ! empty($data['slug']))) {
            $category->translations()->create([
                'locale' => $data['locale'],
                'name' => $data['name'] ?? '',
                'slug' => $data['slug'] ?? '',
            ]);
        }

        return $category->load('translations');
    }

    public function updateCategory(BlogCategory $category, array $data): BlogCategory
    {
        $category->update([
            'parent_id' => $data['parent_id'] ?? $category->parent_id,
        ]);

        if (! empty($data['translations']) && is_array($data['translations'])) {
            foreach ($data['translations'] as $t) {
                if (empty($t['locale'])) {
                    continue;
                }
                $translation = $category->translations()->firstOrNew([
                    'locale' => $t['locale'],
                ]);
                $translation->name = $t['name'] ?? $translation->name ?? '';
                $translation->slug = $t['slug'] ?? $translation->slug ?? '';
                $translation->save();
            }
        } elseif (! empty($data['locale'])) {
            $translation = $category->translations()->where('locale', $data['locale'])->first();
            if ($translation) {
                $translation->update([
                    'name' => $data['name'] ?? $translation->name,
                    'slug' => $data['slug'] ?? $translation->slug,
                ]);
            } else {
                $category->translations()->create([
                    'locale' => $data['locale'],
                    'name' => $data['name'] ?? '',
                    'slug' => $data['slug'] ?? '',
                ]);
            }
        }

        return $category->refresh()->load('translations');
    }

    public function deleteCategory(BlogCategory $category): void
    {
        $category->delete();
    }

    public function findByLocaleAndSlug(string $locale, string $slug): ?BlogCategory
    {
        return BlogCategory::whereHas('translations', function ($q) use ($locale, $slug) {
            $q->where('locale', $locale)->where('slug', $slug);
        })->with(['translations' => function ($q) use ($locale) {
            $q->where('locale', $locale);
        }])->first();
    }
}
