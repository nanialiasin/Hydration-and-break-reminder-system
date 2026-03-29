<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HydrationSession extends Model
{
    protected $fillable = [
        'athlete_id',
        'coach_id',
        'assigned_by_coach',
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
        'started_at',
    ];

    protected $casts = [
        'assigned_by_coach' => 'boolean',
        'completed_at' => 'datetime',
        'started_at' => 'datetime',
    ];
}
