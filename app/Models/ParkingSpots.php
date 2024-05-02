<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpots extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_name',
        'available_time',
        'photos',
        'google_map',
        'latitude',
        'longitude',
        'available_slots',
        'from_date_time',
        'to_date_time',
        'nearby_places',
        'vehicle_types',
        'vehicle_fees',
        'status',

    ];

    protected $attributes = [
        'status' => 0, // Set default value for 'status'
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function authOwner()
    {
        return $this->belongsTo(AuthOwner::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // public function bookings()
    // {
    //     return $this->hasMany(Booking::class);
    // }
}
