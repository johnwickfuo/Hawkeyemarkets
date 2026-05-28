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
        Schema::create('bond_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('cusip');
            $table->string('bond_name');
            $table->decimal('amount', 20, 10); // Principal
            $table->decimal('coupon', 10, 5); // Rate
            $table->decimal('interest_amount', 20, 10); // Calculated ROI
            $table->unsignedBigInteger('issue_date');
            $table->unsignedBigInteger('maturity_date');
            $table->enum('status', ['active', 'matured'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bond_holdings');
    }
};
