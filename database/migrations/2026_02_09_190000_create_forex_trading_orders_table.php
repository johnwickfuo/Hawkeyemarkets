<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('forex_trading_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('symbol');
            $table->enum('type', ['Buy', 'Sell']);
            $table->enum('order_type', ['Market', 'Limit', 'Stop']);
            $table->decimal('volume', 18, 4); // Lots
            $table->decimal('price', 18, 8);
            $table->decimal('stop_loss', 18, 8)->nullable();
            $table->decimal('take_profit', 18, 8)->nullable();
            $table->enum('status', ['pending', 'filled', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forex_trading_orders');
    }
};
