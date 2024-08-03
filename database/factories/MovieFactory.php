<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Movie;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'room_id' => \App\Models\Room::factory(),
            'title' => $this->faker->sentence(3),
            'poster' => $this->faker->imageUrl(),
            'duration' => $this->faker->randomElement([90, 120, 150, 180]),
            'start_time' => Carbon::now()->addHours(rand(1, 48)),
        ];
    }
}
