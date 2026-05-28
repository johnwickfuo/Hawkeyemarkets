<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->to_store() as $key => $value) {
            updateSetting($key, $value);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }

    private function getKycSetting()
    {
        return [
            [
                'name' => 'Deposits',
                'route_wildcard' => 'user.deposits.*',
                'description' => 'When enabled, users must complete KYC verification before making deposits into their account.',
                'status' => 'disabled'
            ],
            [
                'name' => 'Capital Instruments',
                'route_wildcard' => 'user.capital-instruments.*',
                'description' => 'When enabled, users must complete KYC verification before buying or selling capital market instruments such as Stocks, Bonds, or ETFs.',
                'status' => 'disabled'
            ],
            [
                'name' => 'Trading',
                'route_wildcard' => 'user.trading.*',
                'description' => 'When enabled, users must complete KYC verification before accessing trading features including Futures, Margin, or Forex trading.',
                'status' => 'disabled'
            ],
            [
                'name' => 'Investment',
                'route_wildcard' => 'user.investments.*',
                'description' => 'When enabled, users must complete KYC verification before participating in investment plans or managed portfolios.',
                'status' => 'disabled'
            ],
            [
                'name' => 'Withdrawal',
                'route_wildcard' => 'user.withdrawals.*',
                'description' => 'When enabled, users must complete KYC verification before requesting or processing withdrawals.',
                'status' => 'enabled'
            ],
        ];
    }


    private function to_store()
    {
        return [
            'kyc' => $this->getKycSetting()
        ];
    }

};
