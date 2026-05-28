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
        Schema::create('withdrawals', function (Blueprint $table) {
            $structured_data_format = [
                "crypto" => [
                    'transaction_hash' => 'b7f3e2a1c9d84f6e0a5b3c7d9e1f4a8b2c6d5e7f9a0b1c2d3e4f5a6b7c8',
                    'wallet_address' => 'TN1Z3YwRk9sQH2LJ8a6Xx4EwQb5F3M7P9C',
                    'currency' => 'USDT',
                    'network' => 'TRC20',
                ],

                'bank_transfer' => [
                    'bank_name' => 'Chase Bank',
                    'account_holder' => 'John Doe',
                    'account_number' => '1234567890',
                    'routing_number' => '021000021 (nullable)',
                    'swift' => 'CHASUS33 (nullable)',
                ],

                'digital_wallet' => [
                    'payment_id' => 'PAY-84739201',
                    'identity_id' => 'johndoe@email.com / johndoe123',
                ]
            ];

            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('withdrawal_method_id')->constrained()->cascadeOnDelete();
            $table->string('amount');
            $table->string('converted_amount');
            $table->string('fee_percent');
            $table->string('fee_amount');
            $table->string('amount_payable');
            $table->string('exchange_rate');
            $table->string('transaction_reference');
            $table->string('transaction_hash')->nullable();
            $table->string('payment_proof')->nullable();
            $table->string('currency');
            $table->longText('structured_data')->comment(json_encode($structured_data_format));
            $table->longText('auto_res_dump')->nullable()->comment("Dump of response from third party payment provider");
            $table->enum('status', ['pending', 'completed', 'failed', 'partial_payment'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
