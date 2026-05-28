<?php

use App\Models\ManagementTeam;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('management_teams', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['ceo', 'cto', 'coo', 'cmo', 'cfo', 'quant', 'others']);
            $table->string('name');
            $table->string('image');
            $table->text('description');
            $table->timestamps();
        });


        // insert defaults
        $teams = [
            [
                'role' => 'ceo',
                'name' => 'Marcus Sterling',
                'description' => 'Former head of Systematic Trading at a Tier-1 investment bank, Marcus founded :site_name to bridge the gap between retail investors and institutional-grade strategies.',
                'image' => 'ceo.png',
            ],
            [
                'role' => 'cto',
                'name' => 'Elena Vance',
                'description' => 'Cybersecurity architect with over 15 years specializing in distributed ledger technology and high-performance matching engines.',
                'image' => 'cto.png',
            ],
            [
                'role' => 'quant',
                'description' => 'PhD in Quantitative Finance, Dr. Thorne oversees our algorithmic model development and institutional risk management protocols.',
                'name' => 'Dr. Julian Thorne',
                'image' => 'asset_head.png',
            ],
            [
                'role' => 'coo',
                'description' => 'Leading our legal and regulatory frameworks, Sarah ensures every aspect of the :site_name ecosystem complies with international financial standards and SEC guidelines.',
                'name' => 'Sarah Jenkins',
                'image' => 'compliance_head.png'
            ]
        ];

        foreach ($teams as $team) {
            ManagementTeam::create($team);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_teams');
    }
};
