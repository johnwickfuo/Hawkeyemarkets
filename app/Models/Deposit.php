<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'amount',
        'converted_amount',
        'fee_percent',
        'fee_amount',
        'total_amount',
        'exchange_rate',
        'transaction_reference',
        'transaction_hash',
        'payment_proof',
        'expires_at',
        'currency',
        'structured_data',
        'auto_res_dump',
        'status',
    ];


    // cast
    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'amount' => 'decimal:' . $decimal_places,
            // 'converted_amount' =>  //
            'fee_percent' => 'decimal:' . $decimal_places,
            'fee_amount' => 'decimal:' . $decimal_places,
            'total_amount' => 'decimal:' . $decimal_places,
            'exchange_rate' => 'decimal:' . $decimal_places,
            'expires_at' => 'integer',
        ];
    }

    //define the relationship with the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // define relationship to the payment method
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);

    }


    // failed scope
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }


    // pending scope
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // completed scope
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // successful scope (alias for completed)
    public function scopeSuccessful($query)
    {
        return $this->scopeCompleted($query);
    }

    // rejected scope (alias for failed)
    public function scopeRejected($query)
    {
        return $this->scopeFailed($query);
    }


}
