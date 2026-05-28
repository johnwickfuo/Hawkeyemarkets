<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    protected $fillable = [
        'user_id',
        // 'first_name',
        // 'last_name',
        'date_of_birth',
        'phone',
        'phone_code',
        'country',
        'address_line_1',
        'city',
        'zip',
        'document_type',
        'document_front',
        'document_back',
        'selfie',
        'proof_address',
        'status',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
