<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeThemeMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lozand:mail {name} {--m|markdown : Create a new markdown template for the mailable} {--f|force : Create the class even if the mailable already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new email class with a theme-specific view';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $isMarkdown = $this->option('markdown');
        $force = $this->option('force');
        $template = config('site.template');

        if (!$template) {
            $this->error('Site template is not configured in config/site.php.');
            return 1;
        }

        $params = ['name' => $name];
        if ($force) {
            $params['--force'] = true;
        }

        if ($isMarkdown) {
            // Convert standard naming "EmailVerification" to kebab "email-verification"
            // If the user already provided a path or kebab string, rely on Str::kebab to handle or clean it
            $viewName = Str::kebab(class_basename($name));
            $viewPath = "templates.{$template}.mail.{$viewName}";

            $params['--markdown'] = $viewPath;

            $this->info("Generating mailable for template: {$template}");
            $this->info("View path: resources/views/" . str_replace('.', '/', $viewPath) . ".blade.php");
        }

        if ($this->call('make:mail', $params) === 0 && $isMarkdown) {
            // Post-process the generated file to make the view dynamic
            $path = app_path('Mail/' . str_replace('\\', '/', $name) . '.php');

            if (file_exists($path)) {
                $content = file_get_contents($path);

                // Construct the dynamic view logic 
                $dynamicLogic = "\n        // Dynamically resolve view based on active template\n";
                $dynamicLogic .= "        \$template = config('site.template');\n";
                $dynamicLogic .= "        \$view = \"templates.{\$template}.mail.{$viewName}\";\n\n";
                $dynamicLogic .= "        return new Content(\n";
                $dynamicLogic .= "            markdown: \$view,";

                // Replace the standard content return block
                $pattern = '/return\s+new\s+Content\(\s*markdown:\s*[\'"]templates\.' . preg_quote($template) . '\.mail\.' . preg_quote($viewName) . '[\'"],/s';

                if (preg_match($pattern, $content)) {
                    $content = preg_replace($pattern, trim($dynamicLogic), $content);
                    file_put_contents($path, $content);
                    $this->info("Updated mailable to use dynamic view path.");
                }
            }
        }
    }
}
