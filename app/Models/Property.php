<?php

namespace App\Models;

use App\Enums\PropertyStatus;
use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use SoftDeletes;

    protected $table = 'properties';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'price',
        'rooms',
        'garages',
        'type',
        'status',
        'address_line',
        'postal_code',
        'city',
        'region',
        'country',
        'main_image',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rooms' => 'integer',
        'garages' => 'integer',
        'status' => PropertyStatus::class,
        'type' => PropertyType::class,
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(static function (self $model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PropertyTranslation::class, 'property_id', 'id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'id')->orderBy('position');
    }

    public function getTranslation(string $locale, bool $fallback = true): ?PropertyTranslation
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

    public function scopeAvailable($query)
    {
        return $query->where('status', PropertyStatus::AVAILABLE);
    }
}
