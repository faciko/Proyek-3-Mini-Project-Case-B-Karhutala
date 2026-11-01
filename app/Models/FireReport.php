<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FireReport extends Model
{
    protected $fillable = [
        'user_id',
        'area_id',
        'reviewer_id',
        'title',
        'description',
        'occurred_at',
        'latitude',
        'longitude',
        'condition_level',
        'status',
        'evidence_path',
        'review_note',
        'forwarded_to',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
