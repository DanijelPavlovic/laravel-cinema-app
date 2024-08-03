<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::all();
    }

    public function store(CreateMovieRequest $request)
    {

        $posterPath = $request->file('poster')->store('posters', 'public');

        $movie = Movie::create([
            'room_id' => $request->room_id,
            'title' => $request->title,
            'poster' => $posterPath,
            'duration' => $request->duration,
            'start_time' => $request->start_time,
        ]);

        return response()->json($movie, 201);
    }

    public function show(Movie $movie)
    {
        return $movie;
    }

    public function update(UpdateMovieRequest $request, Movie $movie)
    {

        $posterPath = $movie->poster;

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $movie->update([
            'room_id' => $request->room_id,
            'title' => $request->title,
            'poster' => $posterPath,
            'start_time' => $request->start_time,
        ]);

        return response()->json($movie, 200);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return response()->json(null, 204);
    }

    public function getMoviesByRoom(Room $room)
    {
        return $room->movies;
    }
}
