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
        $existingBooking = Booking::where('movie_id', $movie->id)
            ->where('row', $request->row)
            ->where('seat', $request->seat)
            ->first();

        if ($existingBooking) {
            return response()->json(['message' => 'Seat already booked'], 409);
        }

        $booking = new Booking([
            'movie_id' => $movie->id,
            'row' => $request->row,
            'seat' => $request->seat,
        ]);

        $booking->save();

        return response()->json($booking, 201);
    }
}
