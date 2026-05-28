<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{

    // fillables
    protected $fillable = [
        'user_id',
        'withdrawal_method_id',
        'amount',
        'converted_amount',
        'fee_percent',
        'fee_amount',
        'amount_payable',
        'exchange_rate',
        'transaction_reference',
        'transaction_hash',
        'payment_proof',
        'currency',
        'structured_data',
        'auto_res_dump',
        'status',
    ];

    // casts
    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'amount' => 'decimal:' . $decimal_places,
            'fee_percent' => 'decimal:' . $decimal_places,
            'fee_amount' => 'decimal:' . $decimal_places,
            'amount_payable' => 'decimal:' . $decimal_places,
            'exchange_rate' => 'decimal:' . $decimal_places,
        ];
    }

    // relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function withdrawalMethod()
    {
        return $this->belongsTo(WithdrawalMethod::class);
    }

    /**
     * Scope a query to only include approved withdrawals.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending withdrawals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include rejected withdrawals.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'failed');
    }
}
