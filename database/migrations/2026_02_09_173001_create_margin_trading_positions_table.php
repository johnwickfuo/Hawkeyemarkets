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
        Schema::create('margin_trading_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ticker');
            $table->string('side');
            $table->decimal('size', 28, 8);
            $table->decimal('entry_price', 28, 8);
            $table->decimal('current_price', 28, 8);
            $table->decimal('margin', 28, 8);
            $table->decimal('leverage', 8, 2);
            $table->decimal('unrealized_pnl', 28, 8)->default(0);
            $table->decimal('realized_pnl', 28, 8)->default(0);
            $table->unsignedBigInteger('timestamp');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('margin_trading_positions');
    }
};
