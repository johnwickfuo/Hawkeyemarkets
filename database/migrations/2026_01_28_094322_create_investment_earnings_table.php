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
        Schema::create('investment_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('investment_id')->constrained()->cascadeOnDelete();
            $table->string('amount');
            // Classification
            $table->enum('interest', array_keys(config('interests')));  //.eg stocks_and_etfs or businesses_and_startups, etc
            $table->enum('risk_profile', ['conservative', 'balanced', 'growth']);
            $table->enum('investment_goal', ['short_term', 'medium_term', 'long_term']);
            $table->string('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_earnings');
    }
};
