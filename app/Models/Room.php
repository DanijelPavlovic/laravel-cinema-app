<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rows', 'seats_per_row'];

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
