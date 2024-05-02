<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancelledBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'cancelled_by',
        'total_hours',
        'cancelled_date',
        'refund_status',
        'reason_for_cancellation',
        'refund_amount',
        'reason_for_refund',
        'refund_on',
        'comments',
    ];

    // Define the relationship with Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
