<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = [
        'name',
        'email',
        'sport',
        'phone_number',
        'team_name',
        'profile_pic',
        'coach_id',
        'stay_logged_in'
    ];

    public static function generateCoachId()
    {
        $letters = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
        $numbers = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        return 'C' . $letters . $numbers;
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($coach) {
            if (empty($coach->coach_id) || !preg_match('/^C[A-Z]{2}\d{3}$/', $coach->coach_id)) {
                $coach->coach_id = self::generateCoachId();
            }
        });

        // Cascade delete all related data when coach is deleted
        static::deleting(function ($coach) {
            // Get all athletes created by this coach
            $athletes = Athlete::where('created_by_coach', $coach->coach_id)->get();
            
            // Delete each athlete and their related data (cascades through Athlete model)
            foreach ($athletes as $athlete) {
                $athlete->delete();
            }
        });
    }
}