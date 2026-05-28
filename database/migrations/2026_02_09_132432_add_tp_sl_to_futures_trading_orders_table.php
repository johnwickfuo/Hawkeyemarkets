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
        Schema::table('futures_trading_orders', function (Blueprint $table) {
            $table->decimal('take_profit', 20, 8)->nullable()->after('price');
            $table->decimal('stop_loss', 20, 8)->nullable()->after('take_profit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('futures_trading_orders', function (Blueprint $table) {
            $table->dropColumn(['take_profit', 'stop_loss']);
        });
    }
};
