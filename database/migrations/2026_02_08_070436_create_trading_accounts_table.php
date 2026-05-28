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
        Schema::create('trading_accounts', function (Blueprint $table) {
            $table->id();
            //general values
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('account_type', ['spot', 'futures', 'margin', 'forex']);
            $table->enum('account_status', ['active', 'inactive', 'suspended', 'closed']);
            $table->string('balance')->default(0.0);
            $table->string('currency');
            //forex only
            $table->enum('mode', ['live', 'demo']);
            $table->string('equity')->default(0.0);
            $table->enum('level', ['micro', 'mini', 'standard', 'pro', 'vip']);
            $table->string('margin_call')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_accounts');
    }
};
