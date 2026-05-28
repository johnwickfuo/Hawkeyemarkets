<?php

namespace App\Console\Commands;

use App\Models\Investment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CycleOutInvestment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:cycle-out-investment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cycle out investment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interest = array_keys(config('interests'));
        // output interests in console;
        $this->info(implode(', ', $interest));
        return;
        // stop all outgoing email
        $current_email_settings = json_decode(getSetting('email_notifications'), true);
        $new_email_settings = $current_email_settings;

        // disable transaction, investment
        $new_email_settings['transaction'] = 'disabled';
        $new_email_settings['investment'] = 'disabled';
        updateSetting('email_notifications', $new_email_settings);

        // clear cache
        Artisan::call('optimize:clear');

        // chuncked operation, select only active investments
        Investment::where('status', 'active')->chunk(1, function ($investments) {
            foreach ($investments as $investment) {
                $this->info("Processing investment: " . $investment->id);
                $this->cycleOutInvestment($investment);
            }
        });

        // revert to the older email setting
        updateSetting('email_notifications', $current_email_settings);

        $this->info('investments cycled out successfully');
        return Command::SUCCESS;
    }

    private function cycleOutInvestment(Investment $investment)
    {
        $current_iteration = 0;
        $needed_iterations = $investment->total_cycles - $investment->cycle_count;

        while ($current_iteration < $needed_iterations) {
            $investment->update([
                'next_roi_at' => now()->addMinutes(-10)->timestamp,
            ]);
            Artisan::call('lozand:investment-cron-job');
            $current_iteration++;
        }
    }
}
