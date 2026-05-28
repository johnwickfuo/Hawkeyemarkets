<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:delete-log';

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
        //delete log file if its more than or equal to 1mb
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile) && filesize($logFile) >= 1024 * 1024) {
            unlink($logFile);
        }

        updateLastCronJob($this->signature);
        $this->info("Log file deleted successfully");
    }
}
