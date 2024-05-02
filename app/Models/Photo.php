<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['photo_path'];

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }
}
