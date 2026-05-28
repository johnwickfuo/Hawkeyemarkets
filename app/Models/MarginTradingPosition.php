<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarginTradingPosition extends Model
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
        'status'
    ];

    protected function casts(): array
    {
        return [
            'size' => 'decimal:8',
            'entry_price' => 'decimal:8',
            'current_price' => 'decimal:8',
            'margin' => 'decimal:8',
            'leverage' => 'decimal:2',
            'unrealized_pnl' => 'decimal:8',
            'realized_pnl' => 'decimal:8',
            'timestamp' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
