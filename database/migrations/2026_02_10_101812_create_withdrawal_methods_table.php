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
        Schema::create('withdrawal_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->enum('type', ['crypto', 'bank_transfer', 'digital_wallet'])->default('crypto');
            $table->enum('class', ['manual', 'automatic'])->default('manual');
            $table->longText('payment_information')->nullable();
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->string('pay')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_methods');
    }
};
