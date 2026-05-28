<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'image',
        'status',
        'lang',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the admin's name.
     */
    public function getNameAttribute()
    {
        if (config('app.env') === 'sandbox' && session()->has('sandbox_user')) {
            return session()->get('sandbox_user')->name;
        }
        return $this->attributes['name'];
    }

    /**
     * Get the admin's email.
     */
    public function getEmailAttribute()
    {
        if (config('app.env') === 'sandbox' && session()->has('sandbox_user')) {
            return session()->get('sandbox_user')->email;
        }
        return $this->attributes['email'];
    }
}
