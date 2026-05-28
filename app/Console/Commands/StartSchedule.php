<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class StartSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:start-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command triggers schedule:run every 2 seconds, this is useful for shared hosting that does not support cron jobs at seconds level';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $this->dispatchJobs();
        // $this->call('schedule:run');
        $this->executeCommands();
    }


    private function executeCommands()
    {

        // $schedule->command('lozand:delete-notification-message')->daily()->runInBackground();
        // $schedule->command('lozand:investment-cron-job')->everyMinute()->runInBackground();
        // $schedule->command('queue:work --stop-when-empty')->everyThirtySeconds()->withoutOverlapping()->runInBackground();
        // $schedule->command('lozand:update-expired-deposit-deposit')->everyMinute()->runInBackground();
        // $schedule->command('lozand:update-stock-pnl')->everyMinute()->runInBackground();
        $commands = [
            [
                'command' => "lozand:delete-notification-message",
                'delay' => now()->addDay(),
                'flags' => []
            ],
            [
                'command' => "lozand:investment-cron-job",
                'delay' => now()->addMinutes(1),
                'flags' => []
            ],
            [
                'command' => "queue:work",
                'delay' => now()->addSeconds(30),
                'flags' => ['--stop-when-empty' => true]
            ],
            [
                'command' => "lozand:update-expired-deposit-deposit",
                'delay' => now()->addMinutes(1),
                'flags' => []
            ],
            [
                'command' => "lozand:update-stock-pnl",
                'delay' => now()->addMinutes(1),
                'flags' => []
            ],
            [
                'command' => "lozand:manage-futures",
                'delay' => now()->addSeconds(10),
                'flags' => []
            ],
            [
                'command' => "lozand:manage-margin",
                'delay' => now()->addSeconds(10),
                'flags' => []
            ],
            [
                'command' => "lozand:manage-forex",
                'delay' => now()->addSeconds(10),
                'flags' => []
            ],
            [
                'command' => "lozand:delete-log",
                'delay' => now()->addHours(1),
                'flags' => []
            ],
            [
                'command' => 'lozand:site-map-generator',
                'delay' => now()->addMinutes(30),
                'flags' => [],
            ],
            [
                'command' => 'lozand:vulnerability-patch',
                'delay' => now()->addMinute(),
                'flags' => []
            ]
        ];


        foreach ($commands as $command) {
            //check the database  jobs table for the command (in payload), if the command is already queued, skip, else dispatch
            $commandName = $command['command'];
            $cache_key = $commandName . '_running';
            $cache_key = str_replace([':', ' ', '-'], '_', $cache_key);

            if (Cache::get($cache_key)) {
                $this->info('Command ' . $commandName . ' is already running');
                continue;
            }

            //use artisan call
            $this->info('Executing ' . $commandName);
            Artisan::call($commandName, $command['flags']);
            Cache::put($cache_key, true, $command['delay']);

        }

        updateLastCronJob('queue:work');


    }



}
