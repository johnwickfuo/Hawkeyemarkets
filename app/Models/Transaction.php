<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // fillable
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'converted_amount',
        'converted_currency',
        'rate',
        'type',
        'status',
        'reference',
        'description',
        'new_balance',
    ];




    // cast

    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'amount' => 'decimal:' . $decimal_places,
            'converted_amount' => 'decimal:' . $decimal_places,
            'rate' => 'decimal:' . $decimal_places,
            'new_balance' => 'decimal:' . $decimal_places,
        ];
    }



    // relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
