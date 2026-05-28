<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'type',
        'class',
        'payment_information',
        'status',
        'pay'
    ];


    // define relationship with the deposit
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
