<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use function Illuminate\Support\now;

class Investment extends Model
{
    protected $fillable = [
        'user_id',
        'investment_plan_id',
        'capital_invested',
        'compounding_capital',
        'auto_reinvest',
        'roi_earned',
        'next_roi_at',
        'expires_at',
        'status',
        'total_cycles',
        'cycle_count',
    ];

    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'capital_invested' => 'decimal:' . $decimal_places,
            'compounding_capital' => 'decimal:' . $decimal_places,
            'roi_earned' => 'decimal:' . $decimal_places,
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(InvestmentPlan::class, 'investment_plan_id');
    }

    // active investments
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }


    // suspended
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }


    // completed
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // cron // only get active and next roi at has passed
    public function scopeCron($query)
    {
        return $query->where('status', 'active')
            ->where('next_roi_at', '<', now()->timestamp);
    }

    // relationship with the investment earning
    public function investmentEarnings()
    {
        return $this->hasMany(InvestmentEarning::class);
    }
}
