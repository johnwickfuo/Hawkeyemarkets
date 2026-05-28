<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradingAccount extends Model
{
    protected $fillable = [
        'user_id',
        'account_type',
        'account_status',
        'balance',
        'currency',
        'borrowed',
        'mode',
        'equity',
        'level',
        'margin_call',
    ];

    // casts
    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'balance' => 'decimal:' . $decimal_places,
            'borrowed' => 'decimal:' . $decimal_places,
            'equity' => 'decimal:' . $decimal_places,
            'margin_call' => 'decimal:' . $decimal_places,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
