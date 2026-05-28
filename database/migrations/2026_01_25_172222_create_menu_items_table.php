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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();

            $table->string('label');

            // Either route or direct URL
            $table->string('route_name')->nullable();
            $table->string('url')->nullable();

            $table->text('icon')->nullable();

            // Menu type: user, admin, frontend
            $table->enum('type', ['user', 'admin', 'frontend'])->default('frontend');

            // Self-referencing parent
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menu_items')
                ->nullOnDelete();

            // Optional ordering
            $table->unsignedInteger('sort_order')->default(0);

            // Optional visibility
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['type', 'parent_id', 'sort_order']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
