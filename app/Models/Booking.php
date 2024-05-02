<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_spot_id',
        'from_datetime',
        'to_datetime',
        'vehicle_name',
        'vehicle_number',
        'location',
        'status',
        'slot',
        'amount_paid',
        'booked_on',
        'total_hours',
        'pay_id',
        'capture_id',
        'payed_on',
        'payment',
    ];

    // Define the relationship with CancelledBooking
    public function cancelledBooking()
    {
        return $this->hasOne(CancelledBooking::class);
    }

    public function parkingSpots()
    {
        // return $this->belongsTo(ParkingSpots::class);
        return $this->belongsTo(ParkingSpots::class, 'parking_spot_id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(AuthUser::class);
    // }

    public function user()
    {
        return $this->belongsTo(AuthUser::class, 'auth_user_id'); // Specify the foreign key name
    }
}
