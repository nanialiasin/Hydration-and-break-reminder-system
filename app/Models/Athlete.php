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

    public static function generateAthleteId()
    {
        $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
        $numbers = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        return 'A' . $letters . $numbers;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($athlete) {
            // Only generate if athlete_id is not set or does not match the required format
            if (empty($athlete->athlete_id) || !preg_match('/^A[A-Z]{2}\d{3}$/', $athlete->athlete_id)) {
                $athlete->athlete_id = self::generateAthleteId();
            }
        });
    }

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
