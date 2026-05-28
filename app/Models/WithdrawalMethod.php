<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalMethod extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'type',
        'class',
        'payment_information',
        'status',
    ];



    //active scope
    public function scopeActive($query)
    {
        return $query->where('status', 'enabled');
    }

    // withdrawals relationship
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
