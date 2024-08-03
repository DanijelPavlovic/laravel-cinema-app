<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use Carbon\Carbon;

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
        $now = Carbon::now();
        $movies = Movie::whereRaw('DATE_ADD(start_time, INTERVAL duration MINUTE) < ?', [$now])->get();

        foreach ($movies as $movie) {
            $movie->bookings()->delete();
            $this->info('Cleared bookings for movie: ' . $movie->title);
        }

        return 0;
    }
}
