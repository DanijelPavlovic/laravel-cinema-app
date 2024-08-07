<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $movie = Movie::factory()->create();

        $response = $this->getJson(route('movies.index'));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $movie->id,
                'title' => $movie->title,
            ]);
    }

    public function test_store()
    {
        Storage::fake('public');

        $room = Room::factory()->create();

        $file = UploadedFile::fake()->image('poster.jpg');

        $startTime = now()->format('Y-m-d H:i:s');

        $response = $this->postJson(route('movies.store'), [
            'room_id' => $room->id,
            'title' => 'Test Movie',
            'poster' => $file,
            'duration' => 120,
            'start_time' => $startTime,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Movie']);

        $responseData = $response->json();
        $posterPath = parse_url($responseData['poster'], PHP_URL_PATH);
        $posterPath = ltrim($posterPath, '/storage/');

        Storage::disk('public')->assertExists($posterPath);
    }


    public function test_show()
    {
        $movie = Movie::factory()->create();

        $response = $this->getJson(route('movies.show', $movie->id));

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $movie->title]);
    }

    public function test_update()
    {
        Storage::fake('public');

        $movie = Movie::factory()->create();
        $room = Room::factory()->create();

        $file = UploadedFile::fake()->image('new_poster.jpg');

        $startTime = now()->format('Y-m-d H:i:s');

        $response = $this->postJson(route('movies.update', $movie->id), [
            'room_id' => $room->id,
            'title' => 'Updated Movie',
            'poster' => $file,
            'duration' => 120,
            'start_time' => $startTime,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Movie']);

        $responseData = $response->json();
        $posterPath = parse_url($responseData['poster'], PHP_URL_PATH);
        $posterPath = ltrim($posterPath, '/storage/');

        Storage::disk('public')->assertExists($posterPath);
    }


    public function test_destroy()
    {
        $movie = Movie::factory()->create();

        $response = $this->deleteJson(route('movies.destroy', $movie->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }

    public function test_get_movies_by_room()
    {
        $room = Room::factory()->create();
        $movie = Movie::factory()->create(['room_id' => $room->id]);

        $response = $this->getJson('/api/movies/room/' . $room->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $movie->title]);
    }
}
