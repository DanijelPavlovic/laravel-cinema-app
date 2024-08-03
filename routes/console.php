<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('bookings:clear')->everyMinute();
