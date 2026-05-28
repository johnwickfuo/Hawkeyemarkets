<?php

namespace App\Console\Commands;

use App\Models\NotificationMessage;
use Illuminate\Console\Command;

class DeleteNotificationMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:delete-notification-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (getSetting('delete_notification_message') == 'disabled') {
            $this->info("Notification messages deletion is disabled");
            return Command::SUCCESS;
        }

        NotificationMessage::where('status', 'read')->delete();
        $this->info("Notification messages deleted successfully");

        updateLastCronJob($this->signature);

        return Command::SUCCESS;
    }
}
