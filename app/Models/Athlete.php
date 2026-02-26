<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    protected $fillable = [
        'athlete_id',
        'name',
        'email',
        'sport',
        'status',
        'intensity',
        'weight',
        'height',
        'bmi',
        'profile_pic', // allow mass assignment for athlete profile picture
    ];

    public function profile()
    {
        return $this->hasOne(AthleteProfile::class);
    }

    public function getBmiAttribute()
    {
    if ($this->height && $this->weight) {
        $heightInMeters = $this->height / 100;
        $bmi = $this->weight / ($heightInMeters * $heightInMeters);

        if ($bmi < 18.5) return "Underweight";
        if ($bmi < 25) return "Normal Weight";
        if ($bmi < 30) return "Overweight";
        return "Obese";
    }

    return null;
}
}
