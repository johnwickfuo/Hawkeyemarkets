<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentEarning extends Model
{
    protected $fillable = [
        'user_id',
        'investment_id',
        'amount',
        'interest',
        'risk_profile',
        'investment_goal',
        'note',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // cast
    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'amount' => 'decimal:' . $decimal_places,
        ];
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->whereHas('user', function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })->orWhereHas('investment.plan', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('note', 'like', "%$search%");
        });
    }

    public function scopeDateRange($query, $start_date, $end_date)
    {
        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }
        return $query;
    }
}
