<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'city',
        'address',
        'is_approved',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    // ─── Role Helpers ──────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // ─── Relationships ─────────────────────────────

    /**
     * Provider profile (one-to-one).
     */
    public function providerProfile(): HasOne
    {
        return $this->hasOne(ProviderProfile::class);
    }

    /**
     * Provider skills (many-to-many).
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'provider_skills')->withTimestamps();
    }

    /**
     * Provider availabilities.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    /**
     * Provider reviews (has many as provider).
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    /**
     * Customer reviews (has many as customer).
     */
    public function givenReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    /**
     * Bookings made by this user (as customer).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    /**
     * Bookings received by this user (as provider).
     */
    public function providerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    /**
     * Payments made by this user (as customer).
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }

    /**
     * Withdrawals requested by this user (as provider).
     */
    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'provider_id');
    }

    // ─── Accessors ─────────────────────────────────

    /**
     * Get user initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(mb_substr($word, 0, 1));
        }
        return $initials;
    }

    /**
     * Get the profile photo URL.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->providerProfile && $this->providerProfile->profile_photo) {
            return asset('storage/' . $this->providerProfile->profile_photo);
        }
        return null;
    }

    /**
     * Check if provider profile is complete.
     */
    public function getIsProfileCompleteAttribute(): bool
    {
        if (!$this->isProvider()) return false;

        return $this->providerProfile
            && $this->providerProfile->bio
            && $this->providerProfile->hourly_rate > 0
            && $this->skills()->count() > 0;
    }

    /**
     * Get provider's average rating.
     */
    public function getAverageRatingAttribute(): float
    {
        if (!$this->isProvider()) return 0.0;
        return round($this->reviews()->avg('rating') ?? 0.0, 1);
    }

    /**
     * Get provider's total review count.
     */
    public function getReviewCountAttribute(): int
    {
        if (!$this->isProvider()) return 0;
        return $this->reviews()->count();
    }

    /**
     * Get provider's total earnings (completed bookings).
     */
    public function getProviderTotalEarningsAttribute(): float
    {
        return Payment::whereIn('booking_id', $this->providerBookings()->where('status', 'completed')->pluck('id'))
            ->sum('provider_earning');
    }

    /**
     * Get provider's pending earnings (uncompleted/active paid bookings).
     */
    public function getProviderPendingEarningsAttribute(): float
    {
        return Payment::whereIn('booking_id', $this->providerBookings()->whereIn('status', ['pending', 'accepted', 'in_progress'])->pluck('id'))
            ->sum('provider_earning');
    }

    /**
     * Get provider's total withdrawn amount.
     */
    public function getProviderTotalWithdrawnAttribute(): float
    {
        return $this->withdrawals()->where('status', 'approved')->sum('amount');
    }

    /**
     * Get provider's pending withdrawal amount.
     */
    public function getProviderPendingWithdrawalAttribute(): float
    {
        return $this->withdrawals()->where('status', 'pending')->sum('amount');
    }

    /**
     * Get provider's withdrawable balance.
     */
    public function getProviderWithdrawableBalanceAttribute(): float
    {
        return $this->provider_total_earnings - $this->provider_total_withdrawn - $this->provider_pending_withdrawal;
    }
}
