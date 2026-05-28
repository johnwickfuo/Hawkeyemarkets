<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarginTradingOrder extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'order_mode',
        'ticker',
        'side',
        'size',
        'price',
        'leverage',
        'locked_margin',
        'take_profit',
        'stop_loss',
        'status',
        'timestamp'
    ];

    protected function casts(): array
    {
        return [
            'size' => 'decimal:8',
            'price' => 'decimal:8',
            'leverage' => 'decimal:2',
            'locked_margin' => 'decimal:8',
            'take_profit' => 'decimal:8',
            'stop_loss' => 'decimal:8',
            'timestamp' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
