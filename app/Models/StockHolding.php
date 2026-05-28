<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHolding extends Model
{
    protected $fillable = [
        'user_id',
        'ticker',
        'shares',
        'average_price',
        'pnl',
        'pnl_percent',
    ];

    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'shares' => 'decimal:' . $decimal_places,
            'average_price' => 'decimal:' . $decimal_places,
            'pnl' => 'decimal:' . $decimal_places,
            'pnl_percent' => 'decimal:' . $decimal_places,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stockHoldingHistories()
    {
        return $this->hasMany(StockHoldingHistory::class);
    }
}
