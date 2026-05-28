<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('forex_trading_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');
            $table->enum('side', ['Buy', 'Sell']);
            $table->decimal('volume', 18, 4);
            $table->decimal('entry_price', 18, 8);
            $table->decimal('current_price', 18, 8);
            $table->decimal('stop_loss', 18, 8)->nullable();
            $table->decimal('take_profit', 18, 8)->nullable();
            $table->decimal('margin', 18, 8)->default(0);
            $table->decimal('unrealized_pnl', 18, 8)->default(0);
            $table->enum('status', ['open', 'closed', 'liquidated'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forex_trading_positions');
    }
};
