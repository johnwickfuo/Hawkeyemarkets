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
        Schema::create('margin_trading_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['limit', 'market']);
            $table->string('ticker');
            $table->string('side');
            $table->decimal('size', 28, 8);
            $table->decimal('price', 28, 8);
            $table->decimal('leverage', 8, 2)->default(5);
            $table->decimal('locked_margin', 28, 8)->default(0);
            $table->decimal('take_profit', 28, 8)->nullable();
            $table->decimal('stop_loss', 28, 8)->nullable();
            $table->enum('status', ['pending', 'filled', 'canceled'])->default('pending');
            $table->unsignedBigInteger('timestamp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('margin_trading_orders');
    }
};
