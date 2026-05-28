<?php

namespace App\Console\Commands;

use App\Models\Investment;
use App\Models\InvestmentEarning;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InvestmentCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:investment-cron-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle Investment automation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Process only 50 records at a time
        //check if investment module is loaded
        if (!moduleEnabled('investment_module')) {
            $this->info("Investment module is not enabled. Please enable it first.");
            return 0;
        }

        Investment::cron()->chunk(50, function ($investments) {
            foreach ($investments as $investment) {
                try {
                    $this->info("Processing investment: " . $investment->id);
                    $this->manageInvestment($investment);
                } catch (\Exception $e) {
                    Log::error('Investment Cron Job Error: ' . $e->getMessage());
                }
            }
        });

        $this->info("Investment Cron Job completed");

        updateLastCronJob($this->signature);

        return Command::SUCCESS;
    }


    private function manageInvestment($investment)
    {
        //some operation
        $capital = $investment->capital_invested;

        // if the investment plan is compounding, use compounding_capital instead
        if ($investment->plan->compounding) {
            $capital = $investment->compounding_capital;
        }

        // increment cycle count
        $cycle_count = $investment->cycle_count + 1;

        // calculate roi
        $total_profit = $capital * ($investment->plan->return_percent / 100);
        $current_cycle_profit = $total_profit / $investment->total_cycles;

        // // debug
        // $this->info("Current cycle profit: " . $current_cycle_profit);
        // $this->info("Total profit: " . $total_profit);
        // $this->info("Return percent: " . $investment->plan->return_percent);


        // update the investment
        $investment->update([
            'roi_earned' => $investment->roi_earned + $current_cycle_profit,
            'cycle_count' => $cycle_count,
        ]);

        // if the investment is completed, update the status
        if ($investment->cycle_count >= $investment->total_cycles) {
            $investment->update([
                'status' => 'completed',
            ]);
        }

        // if its compounding, update the compounding capital
        if ($investment->plan->compounding) {
            $investment->update([
                'compounding_capital' => $investment->compounding_capital + $current_cycle_profit,
            ]);
        }

        // if its not compounding credit the user, always refresh the user before crediting

        $title = "ROI Earned & Compounded";
        $body = __("You have earned a return of :amount :currency on your investment plan :plan . This been added back to the plan for compounding, its not credited to account balance", [
            'amount' => $current_cycle_profit,
            'plan' => $investment->plan->name,
            'currency' => getSetting('currency'),
        ], $investment->user->lang);

        if (!$investment->plan->compounding) {
            $investment->user->refresh();
            $new_balance = $investment->user->balance + $current_cycle_profit;
            $investment->user->update([
                'balance' => $new_balance,
            ]);

            $title = "ROI Earned";
            $body = __("You have earned a return of :amount :currency on your investment plan :plan . It has  been credited to your account balance", [
                'amount' => $current_cycle_profit,
                'plan' => $investment->plan->name,
                'currency' => getSetting('currency'),
            ], $investment->user->lang);

            $ref = \Str::random(12);
            $description = "ROI Earned";
            recordTransaction($investment->user, $current_cycle_profit, getSetting('currency'), $current_cycle_profit, getSetting('currency'), 1, 'credit', 'completed', $ref, $description, $new_balance);
        }

        // record  notification message
        recordNotificationMessage($investment->user, $title, $body);

        // update the next roi at
        $investment->update([
            'next_roi_at' => getNextReturnTime($investment->plan)->timestamp,
        ]);

        // expires_at is beyond now, expire the plan
        if ($investment->expires_at < now()->timestamp) {
            $investment->update([
                'status' => 'completed',
            ]);

        }
        $interests = $investment->plan->interests;
        //select a random interest from the array
        $interest = $interests[array_rand($interests)];

        $reasons = config('reasons');
        $reasons_by_interest = $reasons[$interest];
        $note = $reasons_by_interest[array_rand($reasons_by_interest)];

        // create new earning record

        $earning = new InvestmentEarning();
        $earning->user_id = $investment->user_id;
        $earning->investment_id = $investment->id;
        $earning->amount = $current_cycle_profit;
        $earning->interest = $interest;
        $earning->risk_profile = $investment->plan->risk_profile;
        $earning->investment_goal = $investment->plan->investment_goal;
        $earning->note = $note;
        $earning->save();

        // refresh the investment and check status
        $investment->refresh();

        // return capital to the user
        if ($investment->status == 'completed') {
            // return capital if plan allows it, if its not allowed, check if the plan is compounding and credit the user total roi earned
            if ($investment->plan->capital_returned) {
                $investment->user->refresh();
                $new_balance = $investment->user->balance + $capital;
                $investment->user->update([
                    'balance' => $new_balance,
                ]);

                $title = "Capital Returned";
                $body = __("Your capital has been returned to your account balance", [], $investment->user->lang);

                $ref = \Str::random(12);
                $description = "Capital Returned";
                recordTransaction($investment->user, $investment->capital_invested, getSetting('currency'), $investment->capital_invested, getSetting('currency'), 1, 'credit', 'completed', $ref, $description, $new_balance);

            } else {
                // if the plan is compounding, credit the total ROI earned
                $investment->user->refresh();
                $new_balance = $investment->user->balance + $investment->roi_earned;
                $investment->user->update([
                    'balance' => $new_balance,
                ]);

                $title = "ROI Earned";
                $body = __("You have earned a return of :amount :currency on your investment plan :plan . It has  been credited to your account balance", [
                    'amount' => $investment->roi_earned,
                    'plan' => $investment->plan->name,
                    'currency' => getSetting('currency'),
                ], $investment->user->lang);

                $ref = \Str::random(12);
                $description = "ROI Earned";
                recordTransaction($investment->user, $investment->roi_earned, getSetting('currency'), $investment->roi_earned, getSetting('currency'), 1, 'credit', 'completed', $ref, $description, $new_balance);
            }


            //send investment email
            $custom_subject = "Investment Completed";
            $custom_message = "Your investment has been completed, if you enabled auto re-investing for this plan, the money earned will be automatically be used to create a new investment.";
            sendInvestmentEmail($custom_subject, $custom_message, $investment);
        }


        // give referral bonus
        try {
            $this->giveReferralBonus($investment->user, $current_cycle_profit);
        } catch (\Exception $e) {
            Log::error('Investment Cron Job Error: ' . $e->getMessage());
        }

        // if the auto re-invest, create a new investment
        if ($investment->auto_reinvest) {
            try {
                $this->createNewInvestment($investment);
            } catch (\Exception $e) {
                Log::error('Investment Cron Job Error: ' . $e->getMessage());
            }
        }







    }

    // create new investment
    private function createNewInvestment(Investment $investment)
    {


        $user = $investment->user;

        // active investment plan,  
        $plan = $investment->plan;


        $currency = getSetting('currency');





        // calculate expires_at based on the plan duration and duration type
        // ['hours', 'days', 'weeks', 'months', 'years'] //duration type
        //['hourly', 'daily', 'weekly', 'monthly', 'yearly'] //return intervals
        $duration_type = ucfirst($plan->duration_type);
        $date_function = "add$duration_type";
        $expires_at = now()->$date_function($plan->duration)->timestamp;

        $next_roi_at = getNextReturnTime($plan);
        $total_cycles = calculateTotalCycles($plan);


        //record investment plan activation
        $new_investment = new Investment();
        $new_investment->user_id = $user->id;
        $new_investment->investment_plan_id = $plan->id;
        $new_investment->capital_invested = $investment->capital_invested;
        $new_investment->compounding_capital = $investment->compounding_capital;
        // $investment->auto_reinvest = $request->auto_reinvest;
        $new_investment->roi_earned = 0;
        $new_investment->next_roi_at = $next_roi_at->timestamp;
        $new_investment->expires_at = $expires_at;
        $new_investment->total_cycles = $total_cycles;
        $new_investment->cycle_count = 0;
        $new_investment->status = 'active';
        $new_investment->save();

        // record new transaction
        $ref = \Str::random(12);
        $amount = $investment->capital_invested;
        recordTransaction($user, $amount, $currency, $amount, $currency, 1, 'debit', 'completed', $ref, 'Investment Plan Activation', $user->balance);

        // record new notification
        $title = "Investment Plan Activation";
        $body = __("You have activated :plan_name plan. Amount: :amount :currency", [
            'plan_name' => $plan->name,
            'amount' => $amount,
            'currency' => $currency
        ], $user->lang);
        recordNotificationMessage($user, $title, $body);

        // send new investment email
        $custom_subject = "Investment Plan Activation";
        $custom_message = "Great news. Your investment plan has been successfully activated and is now live on your account.";
        sendInvestmentEmail($custom_subject, $custom_message, $new_investment);


        return;
    }


    // give referral bonus
    private function giveReferralBonus($user, $base_amount, $current_depth = 0, $referral_bonus_levels = null)
    {
        if ($referral_bonus_levels === null) {
            $referral_bonus_levels = json_decode(getSetting('referral_bonus'), true);
        }

        // Base cases
        if (!is_array($referral_bonus_levels) || !isset($referral_bonus_levels[$current_depth])) {
            return;
        }

        $referrer = $user->referrer;
        if (!$referrer) {
            return;
        }

        $percentage = $referral_bonus_levels[$current_depth];

        if ($percentage > 0) {
            $bonus = $base_amount * ($percentage / 100);

            // Credit the referrer
            $referrer->increment('balance', $bonus);

            // Levels are 1-based for humans, so depth 0 is Level 1
            $human_level = $current_depth + 1;

            // Record Transaction
            $ref = \Str::random(12);
            $currency = getSetting('currency');
            $description = "Referral Bonus (Level $human_level)";
            $new_balance = $referrer->balance;

            recordTransaction(
                $referrer,
                $bonus,
                $currency,
                $bonus,
                $currency,
                1,
                'credit',
                'completed',
                $ref,
                $description,
                $new_balance
            );

            // Notification
            $title = "Referral Bonus Received";
            $body = "You have received a referral bonus of $bonus $currency from your level $human_level referral.";
            recordNotificationMessage($referrer, $title, $body);
        }

        // Recursive call for the next level
        $this->giveReferralBonus($referrer, $base_amount, $current_depth + 1, $referral_bonus_levels);
    }
}
