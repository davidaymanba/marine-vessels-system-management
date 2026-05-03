<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->string('vessel_type')->nullable()->after('vessel_number');
            $table->string('owner_name')->nullable()->after('vessel_type');
            $table->unsignedInteger('capacity')->nullable()->after('owner_name');
            $table->enum('maintenance_status', ['operational', 'maintenance', 'out_of_service'])
                ->default('operational')
                ->after('capacity');
            $table->timestamp('archived_at')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vessels', function (Blueprint $table) {
            $table->dropColumn([
                'vessel_type',
                'owner_name',
                'capacity',
                'maintenance_status',
                'archived_at',
            ]);
        });
    }
};