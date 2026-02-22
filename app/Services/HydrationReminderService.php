<?php

namespace App\Services;

use Carbon\Carbon;

class HydrationReminderService
{
    //Calculate hydration frequency based on environmental stress
    public function calculateInterval($temperature = 25, $humidity = 50, $durationMinutes = 30): int
    {
        $interval = 30;

        // Heat
        if ($temperature >= 35)      $interval -= 10;
        elseif ($temperature >= 30)  $interval -= 7;
        elseif ($temperature >= 27)  $interval -= 3;

        // Humidity
        if ($humidity >= 80)         $interval -= 5;
        elseif ($humidity >= 60)     $interval -= 2;

        // Intensity/Duration
        if ($durationMinutes >= 90)  $interval -= 5;
        
        return max($interval, 10);
    }

    public function calculateNextReminder($startTime, int $intervalMinutes): Carbon
    {
        return Carbon::parse($startTime)->addMinutes($intervalMinutes);
    }

    public function shouldRemind($lastReminderTime, int $intervalMinutes): bool
    {
        $nextTime = Carbon::parse($lastReminderTime)->addMinutes($intervalMinutes);
        return now()->greaterThanOrEqualTo($nextTime);
    }
}