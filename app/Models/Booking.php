<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'customer_id',
        'provider_id',
        'booking_date',
        'start_time',
        'duration',
        'problem_description',
        'total_price',
        'status',
        'cancellation_reason',
    ];

    /**
     * Get the customer who booked.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the service provider.
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    /**
     * Get the payment details associated with this booking.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the review details associated with this booking.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
