<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForexTradingPosition extends Model
{
    protected $fillable = [
        'user_id',
        'symbol',
        'mode',
        'side',
        'volume',
        'entry_price',
        'current_price',
        'stop_loss',
        'take_profit',
        'margin',
        'unrealized_pnl',
        'status',
    ];

    protected $casts = [
        'volume' => 'decimal:4',
        'entry_price' => 'decimal:8',
        'current_price' => 'decimal:8',
        'stop_loss' => 'decimal:8',
        'take_profit' => 'decimal:8',
        'margin' => 'decimal:8',
        'unrealized_pnl' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
