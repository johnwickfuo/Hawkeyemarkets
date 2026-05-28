<?php

namespace App\Services;

class TradingUtility
{
    /**
     * Calculate the Margin Level percentage.
     * Formula: (Equity / Used Margin) * 100
     * Equity = Balance + Unrealized PnL
     *
     * @param float $balance
     * @param float $usedMargin
     * @param float $unrealizedPnL
     * @return float
     */
    public static function calculateMarginLevel(float $balance, float $usedMargin, float $unrealizedPnL): float
    {
        $equity = $balance + $unrealizedPnL;

        if ($usedMargin <= 0) {
            // No margin used, essentially infinite level (safe)
            return 999999;
        }

        return ($equity / $usedMargin) * 100;
    }
}
