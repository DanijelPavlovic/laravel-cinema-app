<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index()
    {
        $bookings = Booking::factory()->count(5)->create();

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_store()
    {
        $data = Booking::factory()->make()->toArray();

        $response = $this->postJson('/api/bookings', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('bookings', $data);
    }

    public function test_show()
    {
        $booking = Booking::factory()->create();

        $response = $this->getJson("/api/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJson($booking->toArray());
    }

    public function test_update()
    {
        $booking = Booking::factory()->create();
        $data = ['row' => 2, 'seat' => 5];

        $response = $this->putJson("/api/bookings/{$booking->id}", $data);

        $response->assertStatus(200)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('bookings', $data);
    }

    public function test_destroy()
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/bookings/{$booking->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_getBookingsByMovie()
    {
        $movie = Movie::factory()->create();
        $bookings = Booking::factory()->count(3)->create(['movie_id' => $movie->id]);

        $response = $this->getJson("/api/bookings/movie/{$movie->id}");

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_bookSeat()
    {
        $movie = Movie::factory()->create();
        $data = [
            'seats' => [
                ['row' => 1, 'seat' => 1],
                ['row' => 2, 'seat' => 1],
            ]
        ];

        $response = $this->postJson("/api/bookings/{$movie->id}/book", $data);

        $response->assertStatus(201)
            ->assertJson(['message' => 'All seats booked successfully']);

        foreach ($data['seats'] as $seat) {
            $this->assertDatabaseHas('bookings', array_merge($seat, ['movie_id' => $movie->id]));
        }
    }

    public function test_bookSeat_alreadyBooked()
    {
        $movie = Movie::factory()->create();
        Booking::factory()->create(['movie_id' => $movie->id, 'row' => 1, 'seat' => 1]);

        $data = [
            'seats' => [
                ['row' => 1, 'seat' => 1],
                ['row' => 2, 'seat' => 1]
            ]
        ];

        $response = $this->postJson("/api/bookings/{$movie->id}/book", $data);

        $response->assertStatus(409)
            ->assertJson([
                'message' => 'Some seats were already booked',
                'failedBookings' => [
                    ['row' => 1, 'seat' => 1]
                ]
            ]);
    }

    public function test_bookSeat_validation()
    {
        $movie = Movie::factory()->create();

        $data = [
            'seats' => [
                ['row' => '', 'seat' => ''],
                ['row' => null, 'seat' => null]
            ]
        ];

        $response = $this->postJson("/api/bookings/{$movie->id}/book", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['seats.0.row', 'seats.0.seat', 'seats.1.row', 'seats.1.seat']);
    }
}
