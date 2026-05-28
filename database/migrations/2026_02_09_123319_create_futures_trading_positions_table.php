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
        Schema::create('futures_trading_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ticker');
            $table->string('side');
            $table->string('size');
            $table->string('entry_price');
            $table->string('current_price');
            $table->string('take_profit');
            $table->string('stop_loss');
            $table->string('margin');
            $table->string('leverage');
            $table->string('unrealized_pnl');
            $table->string('realized_pnl');
            $table->unsignedBigInteger('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('futures_trading_positions');
    }
};
