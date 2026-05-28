<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementTeam extends Model
{
    //filliable
    protected $fillable = [
        'role',
        'name',
        'description',
        'image',
    ];
}
