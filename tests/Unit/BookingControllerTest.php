<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_bookings()
    {
        Booking::factory()->count(5)->create();

        $response = $this->getJson(route('bookings.index'));

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_store_creates_a_booking()
    {
        $bookingData = Booking::factory()->make()->toArray();

        $response = $this->postJson(route('bookings.store'), $bookingData);

        $response->assertStatus(201)
            ->assertJsonFragment($bookingData);

        $this->assertDatabaseHas('bookings', $bookingData);
    }

    public function test_show_returns_a_single_booking()
    {
        $booking = Booking::factory()->create();

        $response = $this->getJson(route('bookings.show', $booking));

        $response->assertStatus(200)
            ->assertJsonFragment($booking->toArray());
    }

    public function test_update_modifies_a_booking()
    {
        $booking = Booking::factory()->create();
        $newData = ['row' => 6, 'seat' => 5];

        $response = $this->putJson(route('bookings.update', $booking), $newData);

        $response->assertStatus(200)
            ->assertJsonFragment($newData);

        $this->assertDatabaseHas('bookings', $newData);
    }

    public function test_destroy_deletes_a_booking()
    {
        $booking = Booking::factory()->create();

        $response = $this->deleteJson(route('bookings.destroy', $booking));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_getBookingsByMovie_returns_bookings_for_specific_movie()
    {
        $movie = Movie::factory()->create();
        $bookings = Booking::factory()->count(3)->create(['movie_id' => $movie->id]);

        $response = $this->getJson(route('movies.bookings', $movie));

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_bookSeat_books_a_seat_for_a_movie()
    {
        $movie = Movie::factory()->create();
        $seatData = [
            'seats' => [
                ['row' => 2, 'seat' => 1],
                ['row' => 3, 'seat' => 1],
                ['row' => 4, 'seat' => 1],
            ]
        ];

        $response = $this->postJson(route('movies.bookSeat', $movie), $seatData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'All seats booked successfully']);

        foreach ($seatData['seats'] as $seat) {
            $this->assertDatabaseHas('bookings', array_merge($seat, ['movie_id' => $movie->id]));
        }
    }

    public function test_bookSeat_returns_conflict_if_seat_already_booked()
    {
        $movie = Movie::factory()->create();
        $seatData = [
            'seats' => [
                ['row' => 3, 'seat' => 1],
                ['row' => 4, 'seat' => 2]
            ]
        ];

        // Pre-book one of the seats
        Booking::create(['movie_id' => $movie->id, 'row' => 3, 'seat' => 1]);

        $response = $this->postJson(route('movies.bookSeat', $movie), $seatData);

        $response->assertStatus(409)
            ->assertJson([
                'message' => 'Some seats were already booked',
                'failedBookings' => [
                    ['row' => 3, 'seat' => 1]
                ]
            ]);
    }
}
