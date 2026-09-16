<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultingSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'service',
        'details',
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
}
