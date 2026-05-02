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
        Schema::table('movements', function (Blueprint $table) {
            $table->foreign('vessel_id')->references('id')->on('vessels')->cascadeOnDelete();
            $table->foreign('exit_id')->references('id')->on('exits')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->dropForeign(['vessel_id']);
            $table->dropForeign(['exit_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
