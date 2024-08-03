<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::all();
    }

    public function store(CreateRoomRequest $request)
    {
        $room = Room::create($request->all());

        return response()->json($room, 201);
    }

    public function show(Room $room)
    {
        return $room;
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->all());
        return response()->json($room, 200);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(null, 204);
    }
}
