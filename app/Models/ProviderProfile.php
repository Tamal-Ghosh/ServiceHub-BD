<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'area',
        'hourly_rate',
        'profile_photo',
        'experience_years',
    ];

    protected function casts(): array
    {
        return [
            'hourly_rate' => 'decimal:2',
            'experience_years' => 'integer',
        ];
    }

    /**
     * Get the user that owns this profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
