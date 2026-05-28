<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    protected $fillable = [
        'user_id',
        'risk_profile',
        'investment_goal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
