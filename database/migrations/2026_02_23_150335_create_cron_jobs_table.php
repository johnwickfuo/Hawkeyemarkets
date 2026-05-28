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
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('command')->unique();
            $table->integer('recommended');
            $table->unsignedBigInteger('last_run');
            $table->timestamps();
        });


        $commands = [
            [
                'command' => "lozand:delete-notification-message",
                'recommended' => 24 * 60 * 60,
                'last_run' => 1769169980,
            ],
            [
                'command' => "lozand:investment-cron-job",
                'recommended' => 60,
                'last_run' => 1769169980,
            ],
            [
                'command' => "queue:work",
                'recommended' => 30,
                'last_run' => 1769169980,
            ],
            [
                'command' => "lozand:update-expired-deposit-deposit",
                'recommended' => 60,
                'last_run' => 1769169980,
            ],
            [
                'command' => "lozand:update-stock-pnl",
                'recommended' => 60,
                'last_run' => 1769169980,
            ],
            [
                'command' => "lozand:manage-futures",
                'recommended' => 10,
                'last_run' => 1769169980,
            ],
            [
                'command' => "lozand:manage-margin",
                'recommended' => 10,
                'last_run' => 1769169980,
            ],
            [
                'command' => "lozand:manage-forex",
                'recommended' => 10,
                'last_run' => 1769169980,
            ],
            [
                'command' => "lozand:delete-log",
                'recommended' => 60 * 60,
                'last_run' => 1769169980,
            ],
        ];

        foreach ($commands as $command) {
            CronJob::updateOrCreate([
                'command' => $command['command'],
                'recommended' => $command['recommended'],
                'last_run' => $command['last_run'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_jobs');
    }
};
