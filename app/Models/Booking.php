<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'row', 'seat'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($booking) {
            // Clear all bookings when a movie is deleted
            $booking->movie->bookings()->delete();
        });
    }

}
