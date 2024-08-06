<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\BookingController;


Route::get('rooms/total', [RoomController::class, 'totalRooms'])->name('totalRooms');
Route::apiResource('rooms', RoomController::class);
Route::get('movies/total', [MovieController::class, 'totalMovies'])->name('totalMoves');
Route::apiResource('movies', MovieController::class)->except('update');
Route::post('movies/update/{movie}', [MovieController::class, 'update'])->name('movies.update');
Route::get('movies/room/{room}', [MovieController::class, 'getMoviesByRoom']);
Route::get('bookings/total', [BookingController::class, 'totalBookings'])->name('totalBookings');
Route::apiResource('bookings', BookingController::class);
Route::get('bookings/movie/{movie}', [BookingController::class, 'getBookingsByMovie'])->name('movies.bookings');
Route::post('bookings/{movie}/book', [BookingController::class, 'bookSeat'])->name('movies.bookSeat');


