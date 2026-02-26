<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HydrationSetting extends Model
{
    protected $fillable = [
        'intensity',
        'hydration_reminder',
        'break_duration',
        'break_reminder',
    ];
}
