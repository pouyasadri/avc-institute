<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'subject',
        'message',
        'page_type',
        'page_name',
        'locale',
        'ip_address',
        'user_agent',
        'gdpr_consent',
        'consent_given_at',
        'privacy_policy_version',
    ];

    protected $casts = [
        'gdpr_consent' => 'boolean',
        'consent_given_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Query scopes for filtering
    public function scopeForPageType($query, string $type)
    {
        return $query->where('page_type', $type);
    }

    public function scopeForPageName($query, string $name)
    {
        return $query->where('page_name', $name);
    }

    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }
}
