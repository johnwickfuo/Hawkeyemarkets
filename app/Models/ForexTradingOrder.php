<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForexTradingOrder extends Model
{
    protected $fillable = [
        'user_id',
        'symbol',
        'mode',
        'type',
        'order_type',
        'volume',
        'price',
        'stop_loss',
        'take_profit',
        'status',
    ];

    protected $casts = [
        'volume' => 'decimal:4',
        'price' => 'decimal:8',
        'stop_loss' => 'decimal:8',
        'take_profit' => 'decimal:8',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
