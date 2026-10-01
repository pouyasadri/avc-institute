<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;

    protected $table = 'blog_categories';

    protected $fillable = [
        'parent_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BlogCategoryTranslation::class, 'blog_category_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id', 'id');
    }

    public function getTranslation(string $locale, bool $fallback = true): ?BlogCategoryTranslation
    {
        // Use the already eager-loaded in-memory collection when available
        // to avoid firing extra DB queries (N+1 prevention)
        $collection = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        $translation = $collection->firstWhere('locale', $locale);

        if (! $translation && $fallback) {
            $fallbackLocale = config('app.fallback_locale', 'en');
            $translation = $collection->firstWhere('locale', $fallbackLocale);
        }

        return $translation;
    }
}
