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
        Schema::table('margin_trading_positions', function (Blueprint $table) {
            $table->decimal('take_profit', 28, 8)->nullable()->after('current_price');
            $table->decimal('stop_loss', 28, 8)->nullable()->after('take_profit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('margin_trading_positions', function (Blueprint $table) {
            $table->dropColumn(['take_profit', 'stop_loss']);
        });
    }
};
