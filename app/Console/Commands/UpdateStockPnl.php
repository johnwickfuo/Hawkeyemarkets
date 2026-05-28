<?php

namespace App\Console\Commands;


use App\Models\EtfHolding;
use App\Models\StockHolding;
use App\Models\BondHolding;
use App\Models\BondHoldingHistory;
use App\Services\LozandServices;
use Illuminate\Console\Command;

class UpdateStockPnl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:update-stock-pnl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock P&L';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        StockHolding::chunkById(20, function (\Illuminate\Database\Eloquent\Collection $holdings) {
            foreach ($holdings as $holding) {
                /** @var StockHolding $holding */
                try {
                    $lozand = new LozandServices();
                    $ticker_data = $lozand->ticker($holding->ticker);
                    if ($ticker_data['status'] == 'success') {
                        $current_price = $ticker_data['data']['current_price'];
                        $pnl = $current_price - $holding->average_price;
                        $pnl_percent = ($pnl / $holding->average_price) * 100;
                        $holding->pnl = $pnl;
                        $holding->pnl_percent = $pnl_percent;
                        $holding->save();
                    }
                } catch (\Exception $e) {
                    // Continue to next holding
                    continue;
                }
            }
        });


        EtfHolding::chunkById(20, function (\Illuminate\Database\Eloquent\Collection $holdings) {
            foreach ($holdings as $holding) {
                /** @var EtfHolding $holding */
                try {
                    $lozand = new LozandServices();
                    $ticker_data = $lozand->etfTicker($holding->ticker);
                    if ($ticker_data['status'] == 'success') {
                        $current_price = $ticker_data['data']['current_price'];
                        $pnl = $current_price - $holding->average_price;
                        $pnl_percent = ($pnl / $holding->average_price) * 100;
                        $holding->pnl = $pnl;
                        $holding->pnl_percent = $pnl_percent;
                        $holding->save();
                    }
                } catch (\Exception $e) {
                    // Continue to next holding
                    continue;
                }
            }
        });

        BondHolding::active()->where('maturity_date', '<=', now()->timestamp)->chunkById(20, function (\Illuminate\Database\Eloquent\Collection $holdings) {
            foreach ($holdings as $holding) {
                /** @var BondHolding $holding */
                try {
                    \DB::transaction(function () use ($holding) {
                        $user = $holding->user;
                        $total_payout = (float) $holding->amount + (float) $holding->interest_amount;

                        $website_currency = getSetting('currency', 'USD');

                        $user->increment('balance', $total_payout);

                        $reference = \Str::orderedUuid();
                        $description = 'Bond Maturity Payout: ' . $holding->cusip;

                        // No conversion needed as per user request
                        $amount_usd = $total_payout;

                        recordTransaction($user, $total_payout, $website_currency, $amount_usd, $website_currency, 1, "credit", "completed", $reference, $description, $user->balance);

                        $holding->update(['status' => 'matured']);

                        BondHoldingHistory::create([
                            'user_id' => $user->id,
                            'bond_holding_id' => $holding->id,
                            'cusip' => $holding->cusip,
                            'amount' => $total_payout,
                            'interest_amount' => $holding->interest_amount,
                            'transaction_type' => 'payout',
                        ]);

                        recordNotificationMessage($user, 'Bond Matured', __('Your bond investment :cusip has matured. A total of :amount has been credited to your balance.', [
                            'cusip' => $holding->cusip,
                            'amount' => $total_payout . ' ' . $website_currency
                        ]));
                    });
                } catch (\Exception $e) {
                    \Log::error('Bond Payout Error: ' . $e->getMessage());
                    continue;
                }
            }
        });

        updateLastCronJob($this->signature);


    }
}
