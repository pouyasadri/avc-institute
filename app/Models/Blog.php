<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    use SoftDeletes;

    protected $table = 'blog_posts';

    protected $fillable = [
        'category_id',
        'author_id',
        'main_image',
        'blog_main_image',
        'is_pinned',
        'published_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Compatibility: service/controllers use blog_main_image; DB column is main_image
     */
    public function getBlogMainImageAttribute(): ?string
    {
        return $this->attributes['main_image'] ?? null;
    }

    public function setBlogMainImageAttribute($value): void
    {
        $this->attributes['main_image'] = $value;
    }

    public function getMainImageUrlAttribute(): ?string
    {
        $path = $this->main_image;
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        if (! str_starts_with($path, 'images/blogs/')) {
            $path = 'images/blogs/'.$path;
        }

        return Storage::disk('s3')->url($path);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id', 'id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BlogPostTranslation::class, 'blog_post_id', 'id');
    }

    public function getTranslation(string $locale, bool $fallback = true): ?BlogPostTranslation
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

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'blog_post_id', 'id');
    }
}
