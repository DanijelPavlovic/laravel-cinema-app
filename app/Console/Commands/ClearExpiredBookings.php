<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class ClearExpiredBookings extends Command
{
    protected $signature = 'bookings:clear';

    protected $description = 'Clear bookings for movies that have ended';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now()->toDateTimeString();
        $this->info('Current time: ' . $now);

        $query = 'SELECT * FROM movies WHERE DATE_ADD(start_time, INTERVAL duration MINUTE) < ?';
        $expiredMovies = DB::select($query, [$now]);
        $this->info('Found ' . count($expiredMovies) . ' movies with expired bookings.');

        foreach ($expiredMovies as $movieData) {

            $movie = Movie::find($movieData->id);
            if ($movie) {
                $this->info('Clearing bookings for movie: ' . $movie->title . ' (ID: ' . $movie->id . ')');
                $deletedCount = $movie->bookings()->delete();
                $this->info('Deleted ' . $deletedCount . ' bookings.');
            }
        }

        return 0;
    }
}
