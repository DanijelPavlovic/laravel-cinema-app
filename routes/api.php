<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RoomController;
use App\Http\Controllers\API\MovieController;
use App\Http\Controllers\API\BookingController;


Route::apiResource('rooms', RoomController::class);
Route::apiResource('movies', MovieController::class);
Route::get('movies/room/{room}', [MovieController::class, 'getMoviesByRoom']);
Route::apiResource('bookings', BookingController::class);
Route::get('bookings/movie/{movie}', [BookingController::class, 'getBookingsByMovie']);
Route::post('bookings/{movie}/book', [BookingController::class, 'bookSeat']);
