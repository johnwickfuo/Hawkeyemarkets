<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientReview extends Model
{
    protected $fillable = [
        'name',
        'role',
        'review',
        'rating',
        'image',
    ];
}
