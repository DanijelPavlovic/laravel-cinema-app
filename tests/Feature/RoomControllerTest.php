<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Room;

class RoomControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_all_rooms()
    {
        Room::factory()->count(3)->create();

        $response = $this->getJson(route('rooms.index'));

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_room()
    {
        $roomData = [
            'name' => 'Room 101',
            'rows' => 10,
            'seats_per_row' => 8
        ];

        $response = $this->postJson(route('rooms.store'), $roomData);

        $response->assertStatus(201)
            ->assertJsonFragment($roomData);

        $this->assertDatabaseHas('rooms', $roomData);
    }

    /** @test */
    public function it_can_show_a_room()
    {
        $room = Room::factory()->create();

        $response = $this->getJson(route('rooms.show', $room->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $room->id,
                'name' => $room->name,
                'rows' => $room->rows,
                'seats_per_row' => $room->seats_per_row
            ]);
    }

    /** @test */
    public function it_can_update_a_room()
    {
        $room = Room::factory()->create();

        $updatedData = [
            'name' => 'Updated Room',
            'rows' => 8,
            'seats_per_row' => 5
        ];

        $response = $this->putJson(route('rooms.update', $room->id), $updatedData);

        $response->assertStatus(200)
            ->assertJsonFragment($updatedData);

        $this->assertDatabaseHas('rooms', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_room()
    {
        $room = Room::factory()->create();

        $response = $this->deleteJson(route('rooms.destroy', $room->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
    }
}
