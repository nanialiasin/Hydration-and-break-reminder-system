<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HydrationSession extends Model
{
    protected $fillable = [
        'sport',
        'intensity',
        'planned_duration_minutes',
        'actual_duration_seconds',
        'temperature',
        'humidity',
        'reminder_interval_minutes',
        'alerts',
        'followed',
        'ignored',
        'hydration_score',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];
}
