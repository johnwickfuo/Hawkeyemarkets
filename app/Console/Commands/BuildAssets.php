<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BuildAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:build {--install} {--push}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build assets and deploy to production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $template = config('site.template');
        $buildPath = public_path('build/assets');

        // Determine options via flags or interactive prompts
        $shouldInstall = $this->option('install') ?: $this->confirm('Do you want to install npm dependencies?', false);
        $shouldPush = $this->option('push') ?: $this->confirm('Do you want to push build assets to the repository?', false);

        $this->components->info("Lozand Asset Pipeline: " . strtoupper($template));

        $this->line('  <bg=blue;fg=white> INFO </> Target Template: <fg=cyan>' . $template . '</>');
        $this->line('  <bg=blue;fg=white> INFO </> Environment: <fg=yellow>' . (config('app.debug') ? 'Development' : 'Production') . '</>');
        $this->line('  <bg=blue;fg=white> INFO </> Install Deps: <fg=' . ($shouldInstall ? 'green' : 'red') . '>' . ($shouldInstall ? 'Yes' : 'No') . '</>');
        $this->line('  <bg=blue;fg=white> INFO </> Auto-Push:    <fg=' . ($shouldPush ? 'green' : 'red') . '>' . ($shouldPush ? 'Yes' : 'No') . '</>');
        $this->newLine();

        // 1. Cleaning Step
        $this->components->task('Cleaning build artifacts', function () use ($buildPath) {
            if (file_exists($buildPath)) {
                shell_exec("rm -rf " . escapeshellarg($buildPath) . " 2>&1");
            }
            return true;
        });

        // 2. NPM Step
        $this->components->task('Verifying Node environment', function () {
            return !empty(shell_exec('which npm'));
        });

        if ($shouldInstall) {
            $this->components->task('Installing dependencies', function () {
                shell_exec('npm install 2>&1');
                return true;
            });
        }

        // 3. Build Step
        $this->components->task('Compiling assets (Vite)', function () {
            shell_exec('npm run build 2>&1');
            return true;
        });

        // 4. Asset Synchronization
        $this->components->task('Synchronizing CSS to public path', function () use ($buildPath, $template) {
            $cssPattern = $buildPath . DIRECTORY_SEPARATOR . 'app-*.css';
            $cssFiles = glob($cssPattern);
            $cssSource = !empty($cssFiles) ? $cssFiles[0] : null;
            $cssDestination = public_path("assets/templates/$template/css/main.css");

            if ($cssSource && file_exists($cssSource)) {
                if (!is_dir(dirname($cssDestination))) {
                    mkdir(dirname($cssDestination), 0755, true);
                }
                copy($cssSource, $cssDestination);
                return true;
            }
            return false;
        });

        $this->components->task('Synchronizing JS to public path', function () use ($buildPath, $template) {
            $jsPattern = $buildPath . DIRECTORY_SEPARATOR . 'app-*.js';
            $jsFiles = glob($jsPattern);
            $jsSource = !empty($jsFiles) ? $jsFiles[0] : null;
            $jsDestination = public_path("assets/templates/$template/js/main.js");

            if ($jsSource && file_exists($jsSource)) {
                if (!is_dir(dirname($jsDestination))) {
                    mkdir(dirname($jsDestination), 0755, true);
                }
                copy($jsSource, $jsDestination);
                return true;
            }
            return false;
        });

        // 5. Optional Deployment
        if ($shouldPush) {
            $this->components->task('Deploying to repository', function () {
                $git_message = 'Build assets at ' . now()->toDateTimeString();
                shell_exec('git add . 2>&1');
                shell_exec('git commit -m "' . $git_message . '" 2>&1');
                shell_exec('git push origin main 2>&1');
                return true;
            });
        }

        $this->newLine();
        $this->line('  <bg=green;fg=black> SUCCESS </> Build completed. Template updated.');
        return Command::SUCCESS;
    }
}
