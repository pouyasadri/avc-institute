<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataRightsRequest extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'email',
        'request_type',
        'status',
        'token',
        'notes_requester',
        'notes_admin',
        'locale',
        'ip_address',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** Request type labels for display */
    public const REQUEST_TYPE_LABELS = [
        'access' => 'Right of Access (Art. 15)',
        'rectification' => 'Right to Rectification (Art. 16)',
        'erasure' => 'Right to Erasure (Art. 17)',
        'portability' => 'Right to Data Portability (Art. 20)',
        'objection' => 'Right to Object (Art. 21)',
        'restriction' => 'Right to Restriction (Art. 18)',
    ];

    /** Whether the request is awaiting admin action */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /** Whether the request has been handled */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}
