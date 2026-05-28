<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investment_plan_id')->constrained()->cascadeOnDelete();
            $table->string('capital_invested');
            $table->string('compounding_capital');
            $table->boolean('auto_reinvest')->default(false);
            $table->string('roi_earned')->default(0);
            $table->unsignedBigInteger('next_roi_at')->nullable();
            $table->unsignedBigInteger('expires_at');
            $table->unsignedBigInteger('total_cycles');
            $table->unsignedBigInteger('cycle_count');
            $table->enum('status', ['active', 'completed', 'suspended'])->default('active');
            $table->timestamps();

            // index is active
            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
