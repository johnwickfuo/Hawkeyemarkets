<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuturesTradingPositions extends Model
{
    protected $fillable = [
        'user_id',
        'ticker',
        'side',
        'size',
        'entry_price',
        'current_price',
        'margin',
        'leverage',
        'unrealized_pnl',
        'realized_pnl',
        'timestamp',
        'take_profit',
        'stop_loss',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'float',
            'entry_price' => 'float',
            'current_price' => 'float',
            'margin' => 'float',
            'leverage' => 'float',
            'unrealized_pnl' => 'float',
            'realized_pnl' => 'float',
            'take_profit' => 'float',
            'stop_loss' => 'float',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
