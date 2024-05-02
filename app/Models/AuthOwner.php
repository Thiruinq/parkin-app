<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class AuthOwner extends Authenticatable
{
    use HasApiTokens,HasFactory;

    protected $fillable = [
        'username',
        'password',
        'email',
        // Add other relevant owner details
    ];

    protected $username = 'username';

    protected $hidden = [
        'password',
    ];

    public function username()
    {
        return 'username';
    }

    public function parkingSpots()
    {
        return $this->hasMany(ParkingSpots::class, 'auth_owner_id');
    }
}
