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
        Schema::table('margin_trading_orders', function (Blueprint $table) {
            $table->enum('order_mode', ['normal', 'borrow', 'repay'])->default('normal')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('margin_trading_orders', function (Blueprint $table) {
            $table->dropColumn('order_mode');
        });
    }
};
