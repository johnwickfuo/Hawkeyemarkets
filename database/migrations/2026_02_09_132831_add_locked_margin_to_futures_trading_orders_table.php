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
            $table->decimal('locked_margin', 20, 8)->default(0)->after('stop_loss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('futures_trading_orders', function (Blueprint $table) {
            $table->dropColumn('locked_margin');
        });
    }
};
