<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RepairTransalations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:repair-translations';

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
        $this->components->info('Starting translation repair...');

        // Correct path to lang directory
        $files = glob(base_path('lang/*.json'));

        if (empty($files)) {
            $this->components->warn('No translation files found in ' . base_path('lang'));
            return;
        }

        foreach ($files as $file) {
            $filename = basename($file);

            $this->components->task("Cleaning $filename", function () use ($file, $filename) {
                $content = file_get_contents($file);

                // Decode JSON
                $data = json_decode($content, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->components->error("Error decoding $filename: " . json_last_error_msg());
                    return false;
                }

                // Sort keys alphabetically
                ksort($data);

                // Re-encode
                $newContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

                file_put_contents($file, $newContent);
                return true;
            });
        }

        $this->components->info('Translation repair completed successfully.');
    }
}
