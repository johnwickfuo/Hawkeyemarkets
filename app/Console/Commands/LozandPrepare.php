<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LozandPrepare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare the script for export';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        updateEnv('USE_VITE', false);
        $this->newLine();
        $this->line(' <bg=blue;fg=white> INFO </> <fg=white;options=bold>Lozand Software Packaging</>');
        $this->newLine();

        $this->components->task('Clearing source application cache', function () {
            $this->callSilent('optimize:clear');
            return true;
        });

        $this->newLine();

        $source = base_path();
        $parent = dirname($source);
        $destination = $parent . DIRECTORY_SEPARATOR . 'lozand-software' . DIRECTORY_SEPARATOR . 'lozand' . DIRECTORY_SEPARATOR . 'Files';

        // Clean destination
        if (file_exists($destination)) {
            $this->components->task('Cleaning previous export directory', function () use ($destination) {
                \Illuminate\Support\Facades\File::deleteDirectory($destination);
                return true;
            });
            $this->newLine();
        }

        \Illuminate\Support\Facades\File::makeDirectory($destination, 0755, true, true);

        // Get all root items
        $items = array_diff(scandir($source), ['.', '..', '.git', '.github', 'node_modules']);

        foreach ($items as $item) {
            $itemPath = $source . DIRECTORY_SEPARATOR . $item;
            $itemDest = $destination . DIRECTORY_SEPARATOR . $item;
            $isDir = is_dir($itemPath);
            $itemTypeString = $isDir ? 'folder' : 'file  ';

            $this->line(" <fg=gray>» Copying {$itemTypeString}</> <fg=white;options=bold>{$item}</>");

            $start = microtime(true);

            try {
                if ($isDir) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        // Use robocopy on Windows (significantly faster)
                        // /E copies subdirectories, including empty ones
                        // /NJH /NJS suppresses headers and summaries
                        // /MT:32 uses multi-threading
                        // /R:0 /W:0 disables retries to avoid hanging on locked files
                        exec("robocopy \"{$itemPath}\" \"{$itemDest}\" /E /NJH /NJS /MT:32 /R:0 /W:0");
                    } else {
                        // Fallback for non-Windows (cp -R is usually faster than PHP)
                        exec("cp -R \"{$itemPath}\" \"{$itemDest}\"");
                    }
                } else {
                    \Illuminate\Support\Facades\File::copy($itemPath, $itemDest);
                }

                $duration = round((microtime(true) - $start) * 1000);
                $durationStr = $duration > 1000 ? round($duration / 1000, 2) . 's' : $duration . 'ms';

                // Perfectly align dots after the label
                $label = " > Copied  {$itemTypeString} {$item} ";
                $dots = str_repeat('.', max(5, 65 - strlen($label)));

                $this->line(" <fg=green>{$label}</><fg=gray>{$dots} {$durationStr}</>");
                $this->newLine();

            } catch (\Exception $e) {
                $this->error("Failed to copy {$item}: " . $e->getMessage());
            }
        }

        // Sanitization Step
        $this->newLine();
        $this->line(' <bg=blue;fg=white> INFO </> <fg=white;options=bold>Sanitizing Environment Configuration</>');
        $this->newLine();

        $this->components->task('Applying security scrubbing to .env', function () use ($destination) {
            $envPath = $destination . DIRECTORY_SEPARATOR . '.env';
            if (!file_exists($envPath))
                return false;

            $content = file_get_contents($envPath);
            $sanity = config('sanityenv', []);

            foreach ($sanity as $key => $value) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            }

            // Generate new APP_KEY
            $newKey = 'base64:' . base64_encode(random_bytes(32));
            $content = preg_replace("/^APP_KEY=.*/m", "APP_KEY={$newKey}", $content);

            file_put_contents($envPath, $content);

            // Sync with .env.example in destination
            file_put_contents($destination . DIRECTORY_SEPARATOR . '.env.example', $content);

            //Sync with env.backup
            file_put_contents($destination . DIRECTORY_SEPARATOR . '.env.backup', $content);

            return true;
        });


        // Housekeeping Step
        $this->newLine();
        $this->line(' <bg=blue;fg=white> INFO </> <fg=white;options=bold>Performing Final Housekeeping</>');
        $this->newLine();

        $this->components->task('Cleaning installation locks and sandbox data', function () use ($destination) {
            $storagePath = $destination . DIRECTORY_SEPARATOR . 'storage';

            // Delete installed.json
            $installedFile = $storagePath . DIRECTORY_SEPARATOR . 'installed.json';
            if (file_exists($installedFile)) {
                unlink($installedFile);
            }

            // Delete sandbox-users.json
            $sandboxFile = $storagePath . DIRECTORY_SEPARATOR . 'sandbox-users.json';
            if (file_exists($sandboxFile)) {
                unlink($sandboxFile);
            }

            // Clean logs directory
            $logPath = $storagePath . DIRECTORY_SEPARATOR . 'logs';
            if (is_dir($logPath)) {
                $files = glob($logPath . DIRECTORY_SEPARATOR . '*');
                foreach ($files as $file) {
                    if (is_file($file) && basename($file) !== '.gitignore') {
                        unlink($file);
                    }
                }
            }

            // Clean storage/app/public subfolders
            $publicPath = $storagePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public';
            if (is_dir($publicPath)) {
                $subfolders = array_filter(glob($publicPath . DIRECTORY_SEPARATOR . '*'), 'is_dir');
                foreach ($subfolders as $folder) {
                    // skip all .txt files


                    $folderName = basename($folder);
                    $this->line(" <fg=gray>  - Clearing storage folder:</> <fg=yellow>{$folderName}</>");

                    $items = glob($folder . DIRECTORY_SEPARATOR . '*');
                    foreach ($items as $item) {
                        if (is_file($item) && strtolower(pathinfo($item, PATHINFO_EXTENSION)) === 'txt') {
                            continue;
                        }

                        if (is_dir($item)) {
                            \Illuminate\Support\Facades\File::deleteDirectory($item);
                        } else {
                            \Illuminate\Support\Facades\File::delete($item);
                        }
                    }
                }
            }

            // remove old storage directory if it was copied (it should be empty/fresh)
            $publicStoragePath = $destination . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'storage';
            if (file_exists($publicStoragePath)) {
                $this->line(" <fg=gray>  - Removing exported storage directory:</> <fg=yellow>public/storage</>");
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    // High-performance removal on Windows
                    exec("rmdir /s /q \"{$publicStoragePath}\"");
                } else {
                    \Illuminate\Support\Facades\File::deleteDirectory($publicStoragePath);
                }
            }
            return true;
        });

        // copy documentation
        $documentation_path = $destination . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'documentation';
        if (is_dir($documentation_path)) {
            $copy_path = dirname($destination) . DIRECTORY_SEPARATOR . 'documentation';

            $this->components->task('Isolating documentation to root folder', function () use ($documentation_path, $copy_path) {
                if (is_dir($copy_path)) {
                    \Illuminate\Support\Facades\File::deleteDirectory($copy_path);
                }
                \Illuminate\Support\Facades\File::copyDirectory($documentation_path, $copy_path);
                \Illuminate\Support\Facades\File::deleteDirectory($documentation_path);
                return true;
            });
        }


        // Delete all templates except 'bento'
        $templatePath = $destination . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'templates';
        if (is_dir($templatePath)) {
            $templates = array_filter(glob($templatePath . DIRECTORY_SEPARATOR . '*'), 'is_dir');
            foreach ($templates as $templateDir) {
                if (basename($templateDir) !== 'bento') {
                    $this->line(" <fg=gray>  - Removing unused template:</> <fg=yellow>" . basename($templateDir) . "</>");
                    \Illuminate\Support\Facades\File::deleteDirectory($templateDir);
                }
            }
        }
        // Archival Step
        $this->newLine();
        $this->line(' <bg=blue;fg=white> INFO </> <fg=white;options=bold>Compiling Final Archive</>');
        $this->newLine();

        $version = config('site.version');
        $formattedVersion = str_replace('.', '-', $version);
        $zipName = 'lozand-v' . $formattedVersion . '.zip';

        $this->components->task('Generating ' . $zipName . ' archive', function () use ($destination, $zipName) {
            $exportFolder = dirname($destination); // .../lozand-software/lozand
            $zipFile = dirname($exportFolder) . DIRECTORY_SEPARATOR . $zipName;

            if (file_exists($zipFile)) {
                unlink($zipFile);
            }

            $zip = new \ZipArchive();
            if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                return false;
            }

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($exportFolder, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    // Extract relative path to keep the 'lozand' parent folder in the zip
                    // Using forward slashes for internal zip paths to ensure compatibility with Linux/cPanel extraction
                    $relativePath = 'lozand/' . str_replace(DIRECTORY_SEPARATOR, '/', substr($filePath, strlen($exportFolder) + 1));
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
            return true;
        });


        // Archive Management
        $this->components->task('Moving archive to storage', function () use ($destination, $zipName) {
            $softwareRoot = dirname(dirname($destination)); // .../lozand-software
            $archiveDir = $softwareRoot . DIRECTORY_SEPARATOR . 'archives';
            $sourceZip = $softwareRoot . DIRECTORY_SEPARATOR . $zipName;
            $destZip = $archiveDir . DIRECTORY_SEPARATOR . $zipName;

            if (!is_dir($archiveDir)) {
                mkdir($archiveDir, 0755, true);
            }

            if (file_exists($destZip)) {
                unlink($destZip);
            }

            if (rename($sourceZip, $destZip)) {
                return true;
            }
            return false;
        });

        $this->newLine();
        $this->line(' <bg=green;fg=black> SUCCESS </> <fg=white>Project exported and archived to </><fg=cyan;options=bold>lozand-software/archives/' . $zipName . '</>');
        $this->newLine();

        updateEnv('USE_VITE', true);

        return Command::SUCCESS;
    }
}
