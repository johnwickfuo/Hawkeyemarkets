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
        // Use the helper function as requested
        updateSetting('preloader', 'enabled');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //nothing here
    }
};
