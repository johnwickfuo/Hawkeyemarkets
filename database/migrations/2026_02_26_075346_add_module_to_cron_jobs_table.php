<?php

use App\Models\CronJob;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cron_jobs', function (Blueprint $table) {
            $table->string('module')->after('last_run')->nullable();
        });

        $commands = [
            [
                'command' => "lozand:delete-notification-message",
                'recommended' => 24 * 60 * 60,
                'last_run' => 1769169980,
                'module' => null,
            ],
            [
                'command' => "lozand:investment-cron-job",
                'recommended' => 60,
                'last_run' => 1769169980,
                'module' => 'investment_module',
            ],
            [
                'command' => "queue:work",
                'recommended' => 30,
                'last_run' => 1769169980,
                'module' => null,
            ],
            [
                'command' => "lozand:update-expired-deposit-deposit",
                'recommended' => 60,
                'last_run' => 1769169980,
                'module' => null
            ],
            [
                'command' => "lozand:update-stock-pnl",
                'recommended' => 60,
                'last_run' => 1769169980,
                'module' => 'stock_module',
            ],
            [
                'command' => "lozand:manage-futures",
                'recommended' => 10,
                'last_run' => 1769169980,
                'module' => 'futures_module',
            ],
            [
                'command' => "lozand:manage-margin",
                'recommended' => 10,
                'last_run' => 1769169980,
                'module' => 'margin_module',
            ],
            [
                'command' => "lozand:manage-forex",
                'recommended' => 10,
                'last_run' => 1769169980,
                'module' => 'forex_module',
            ],
            [
                'command' => "lozand:delete-log",
                'recommended' => 60 * 60,
                'last_run' => 1769169980,
                'module' => null,
            ],
            [
                'command' => "lozand:site-map-generator",
                'recommended' => 30 * 60,
                'last_run' => 1769169980,
                'module' => null,
            ]
        ];

        CronJob::truncate();

        foreach ($commands as $command) {

            CronJob::updateOrCreate([
                'command' => $command['command'],
            ], [
                'recommended' => $command['recommended'],
                'last_run' => $command['last_run'],
                'module' => $command['module'],
            ]);
        }


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cron_jobs', function (Blueprint $table) {
            $table->dropColumn(['module']);
        });
    }
};
