<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookSeatRequest;
use App\Models\Booking;
use App\Models\Movie;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::all();
    }

    public function store(Request $request)
    {
        $booking = Booking::create($request->all());
        return response()->json($booking, 201);
    }

    public function show(Booking $booking)
    {
        return $booking;
    }

    public function update(Request $request, Booking $booking)
    {
        $booking->update($request->all());
        return response()->json($booking, 200);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return response()->json(null, 204);
    }

    public function getBookingsByMovie(Movie $movie)
    {
        return $movie->bookings;
    }

    public function bookSeat(BookSeatRequest $request, Movie $movie)
    {
        $seats = $request->input('seats');
        $failedBookings = [];

        foreach ($seats as $seat) {
            $existingBooking = Booking::where('movie_id', $movie->id)
                ->where('row', $seat['row'])
                ->where('seat', $seat['seat'])
                ->first();

            if ($existingBooking) {
                $failedBookings[] = $seat;
                continue;
            }

            $booking = new Booking([
                'movie_id' => $movie->id,
                'row' => $seat['row'],
                'seat' => $seat['seat'],
            ]);

            $booking->save();
        }

        if (!empty($failedBookings)) {
            return response()->json(['message' => 'Some seats were already booked', 'failedBookings' => $failedBookings], 409);
        }

        return response()->json(['message' => 'All seats booked successfully'], 201);
    }

    public function totalBookings()
    {
        $total = Booking::count();
        return response()->json(['total' => $total], 200);
    }
}
