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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            // Personal Info
            // $table->string('first_name')->nullable();
            // $table->string('last_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_code')->nullable();

            // Address Info
            $table->string('country')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();

            // Documents
            $table->string('document_type')->nullable(); // passport, id_card, drivers_license
            $table->string('document_front')->nullable(); // file path
            $table->string('document_back')->nullable(); // file path (optional for passport)

            $table->string('selfie')->nullable(); // file path
            $table->string('proof_address')->nullable(); // file path

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
