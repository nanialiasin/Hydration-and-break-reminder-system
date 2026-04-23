<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AthleteProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'weight',
        'height',
        'sport',
        'intensity',
        'bmi',
        'hydration_status',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }
}
