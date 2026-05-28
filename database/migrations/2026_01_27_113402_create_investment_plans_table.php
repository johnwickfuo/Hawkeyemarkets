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
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();

            // Core
            $table->string('name');
            $table->text('description')->nullable();

            // Classification
            $table->json('interests')->comment(json_encode(array_keys(config('interests'))));
            $table->enum('risk_profile', ['conservative', 'balanced', 'growth']);
            $table->enum('investment_goal', ['short_term', 'medium_term', 'long_term']);

            // Duration
            $table->unsignedInteger('duration');
            $table->enum('duration_type', ['hours', 'days', 'weeks', 'months', 'years']);

            // Financials
            $table->decimal('min_investment', 18, config('site.decimal_places'));
            $table->decimal('max_investment', 18, config('site.decimal_places'));

            // Returns
            $table->decimal('return_percent', 10, config('site.decimal_places'));
            $table->enum('return_interval', ['hourly', 'daily', 'weekly', 'monthly', 'yearly']);
            $table->boolean('compounding')->default(false);
            $table->boolean('capital_returned')->default(true);

            // Visibility
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_featured')->default(false);

            // Meta
            $table->timestamps();

            // Indexes
            $table->index(['is_enabled', 'is_featured']);
            $table->index('risk_profile');
            $table->index('investment_goal');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};
