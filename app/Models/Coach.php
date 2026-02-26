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
    'profile_picture',
    'coach_id'
    ];
}
