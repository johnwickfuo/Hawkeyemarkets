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
        Schema::create('futures_trading_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['limit', 'market']);
            $table->string('ticker');
            $table->string('side');
            $table->string('size');
            $table->string('price');
            $table->enum('status', ['pending', 'filled', 'canceled']);
            $table->string('order_id');
            $table->unsignedBigInteger('timestamp');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('futures_trading_orders');
    }
};
