<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AthleteProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'weight',
        'height',
        'sport',
        'intensity',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }
}
