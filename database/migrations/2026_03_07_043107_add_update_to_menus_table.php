<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        MenuItem::updateOrCreate(
            [
                'route_name' => 'admin.update.index',
            ],
            [
                'label' => "Update",

                'url' => null,
                'type' => 'admin',
                'sort_order' => 30,
                'is_active' => true,
                'parent_id' => null,
                'route_wildcard' => 'admin.update.*',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.5 9a9 9 0 0 1 14.1-3.4L23 10M1 14l5.4 4.4A9 9 0 0 0 20.5 15"></path></svg>'
            ]
        );


        cache()->forget('admin_menu_items');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
