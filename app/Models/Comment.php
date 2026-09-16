<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'blog_post_id',
        'locale',
        'name',
        'email',
        'subject',
        'body',
        'is_approved',
        'gdpr_consent',
        'consent_given_at',
        'privacy_policy_version',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'gdpr_consent' => 'boolean',
        'consent_given_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_post_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }
}
