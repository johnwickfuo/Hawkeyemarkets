<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuturesTradingOrders extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'ticker',
        'side',
        'size',
        'price',
        'status',
        'order_id',
        'timestamp',
        'take_profit',
        'stop_loss',
        'locked_margin',
        'leverage'
    ];


    protected function casts(): array
    {
        return [
            'size' => 'float',
            'price' => 'float',
            'take_profit' => 'float',
            'stop_loss' => 'float',
            'locked_margin' => 'float',
            'leverage' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
