<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Movie;
use Carbon\Carbon;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            Movie::factory()->count(4)->create([
                'room_id' => $room->id,
                'start_time' => Carbon::now()->addHours(rand(1, 12)),
            ]);
        }
    }
}
