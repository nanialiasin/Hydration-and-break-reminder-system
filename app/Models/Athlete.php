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
        'profile_pic',
        'created_by_coach',
        'alert_volume',
        'reminder_volume',
        'stay_logged_in',
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
            if (empty($athlete->athlete_id) || !preg_match('/^A[A-Z]{2}\d{3}$/', $athlete->athlete_id)) {
                $athlete->athlete_id = self::generateAthleteId();
            }
        });

        // Cascade delete all related data when athlete is deleted
        static::deleting(function ($athlete) {
            // Delete athlete profiles
            $athlete->profile()->delete();
            
            // Delete hydration settings for this athlete
            \App\Models\HydrationSetting::where('athlete_id', $athlete->athlete_id)->delete();
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
