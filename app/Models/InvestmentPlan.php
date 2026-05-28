<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InvestmentPlan extends Model
{
    //

    protected $fillable = [
        'name',
        'description',
        'interests',
        'risk_profile',
        'investment_goal',
        'duration',
        'min_investment',
        'max_investment',
        'return_percent',
        'compounding',
        'capital_returned',
        'is_enabled',
        'is_featured',
    ];

    protected function casts(): array
    {
        $decimal_places = getSetting('decimal_places');
        return [
            'interests' => 'array',
            'is_enabled' => 'boolean',
            'is_featured' => 'boolean',
            'duration' => 'integer',
            'min_investment' => 'decimal:' . $decimal_places,
            'max_investment' => 'decimal:' . $decimal_places,
            'return_percent' => 'decimal:' . $decimal_places,
            'compounding' => 'boolean',
            'capital_returned' => 'boolean',
        ];
    }


    // only active plans
    public function scopeActive($query)
    {
        return $query->where('is_enabled', true);
    }

    // recommended plans based on user's risk profile and investment_goal
    public function scopeRecommended(Builder $query)
    {
        $user = auth()->user();

        // Base query
        $query->where('is_enabled', true);

        // No personalization if onboarding missing
        if (!$user || !$user->onboarding) {
            return $query
                ->orderByDesc('is_featured')
                ->orderBy('min_investment');
        }

        $risk = $user->onboarding->risk_profile;
        $goal = $user->onboarding->investment_goal;

        return $query
            ->select('*')
            ->selectRaw("
            CASE
                WHEN risk_profile = ? AND investment_goal = ? THEN 1
                WHEN risk_profile = ? THEN 2
                WHEN is_featured = 1 THEN 3
                ELSE 4
            END AS relevance_rank
        ", [$risk, $goal, $risk])
            ->orderBy('relevance_rank')
            ->orderByDesc('is_featured')
            ->orderBy('min_investment');
    }

    // inactive scope
    public function scopeInactive($query)
    {
        return $query->where('is_enabled', false);
    }


    // relationship with investment
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }


}
