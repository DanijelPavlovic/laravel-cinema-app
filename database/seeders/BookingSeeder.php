<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = Movie::all();

        foreach ($movies as $movie) {
            Booking::factory()->count(10)->create([
                'movie_id' => $movie->id,
            ]);
        }
    }
}
