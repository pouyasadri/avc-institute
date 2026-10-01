<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasTranslations;
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
}
