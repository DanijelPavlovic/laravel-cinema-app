<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::all();
    }

    public function store(CreateMovieRequest $request)
    {
        $image = $request->file('poster');

        if ($image) {
            $file_name = uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('images', $image, $file_name);
            $posterUrl = Storage::disk('public')->url('images/' . $file_name);
        }

        $movie = Movie::create([
            'room_id' => $request->room_id,
            'title' => $request->title,
            'poster' => $posterUrl,
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

        if ($request->hasFile('poster')) {
            $image = $request->file('poster');
            $file_name = uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('images', $image, $file_name);
            $posterUrl = Storage::disk('public')->url('images/' . $file_name);
        }

        $data = [
            'room_id' => $request->room_id,
            'title' => $request->title,
            'duration' => $request->duration,
            'start_time' => $request->start_time,
        ];

        if (isset($posterUrl)) {
            $data['poster'] = $posterUrl;
        }

        $movie->update($data);

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

    public function totalMovies()
    {
        $total = Movie::count();
        return response()->json(['total' => $total], 200);
    }
}
