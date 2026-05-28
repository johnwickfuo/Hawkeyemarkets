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
        Schema::create('etf_holding_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('etf_holding_id')->constrained()->cascadeOnDelete();
            $table->string('ticker');
            $table->decimal('shares', 20, 10)->default(0);
            $table->decimal('price_at_action', 20, 10)->default(0);
            $table->string('amount');
            $table->string('amount_usd');
            $table->string('fee_amount');
            $table->string('fee_amount_percent');
            $table->enum('transaction_type', ['buy', 'sell']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etf_holding_histories');
    }
};
