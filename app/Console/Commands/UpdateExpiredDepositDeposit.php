<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use Illuminate\Console\Command;

class UpdateExpiredDepositDeposit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:update-expired-deposit-deposit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired deposit deposit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deposits = Deposit::whereIn('status', ['pending'])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now()->timestamp)
            ->get();

        foreach ($deposits as $deposit) {
            $deposit->status = 'failed';
            $deposit->save();

            // Record notification
            $title = 'Deposit Failed';
            $body = 'No payment was received within the expected time frame. Please try again.';
            recordNotificationMessage($deposit->user, $title, $body);

            // Send email as well
            sendDepositEmail($title, $body, $deposit);
        }

        updateLastCronJob($this->signature);

        return Command::SUCCESS;
    }
}
