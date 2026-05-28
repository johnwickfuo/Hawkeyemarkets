<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('forex_trading_orders', function (Blueprint $table) {
            $table->enum('mode', ['live', 'demo'])->after('symbol')->default('live');
        });

        Schema::table('forex_trading_positions', function (Blueprint $table) {
            $table->enum('mode', ['live', 'demo'])->after('symbol')->default('live');
        });
    }

    public function down(): void
    {
        Schema::table('forex_trading_orders', function (Blueprint $table) {
            $table->dropColumn('mode');
        });

        Schema::table('forex_trading_positions', function (Blueprint $table) {
            $table->dropColumn('mode');
        });
    }
};
