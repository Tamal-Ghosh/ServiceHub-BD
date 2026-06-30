<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description',
    ];

    /**
     * Get the providers that have this skill.
     */
    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'provider_skills')->withTimestamps();
    }
}
