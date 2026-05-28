<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHoldingHistory extends Model
{
    protected $fillable = [
        'user_id',
        'stock_holding_id',
        'ticker',
        'shares',
        'price_at_action',
        'amount',
        'amount_usd',
        'fee_amount',
        'fee_amount_percent',
        'transaction_type',
    ];

    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'shares' => 'decimal:' . $decimal_places,
            'price_at_action' => 'decimal:' . $decimal_places,
            'amount' => 'decimal:' . $decimal_places,
            'amount_usd' => 'decimal:' . $decimal_places,
            'fee_amount' => 'decimal:' . $decimal_places,
            'fee_amount_percent' => 'decimal:' . $decimal_places,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stockHolding()
    {
        return $this->belongsTo(StockHolding::class);
    }

    // sold scope
    public function scopeSold($query)
    {
        return $query->where('transaction_type', 'sell');
    }

    // bought scope
    public function scopeBought($query)
    {
        return $query->where('transaction_type', 'buy');
    }
}
