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
        Schema::table('return_reports', function (Blueprint $table) {
            $table->dropColumn('room_id');
            $table->dropColumn('user_id');
            $table->dropColumn('equipment_id');
            $table->unsignedBigInteger('broken_equipment_id');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('return_reports', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('equipment_id');
            $table->dropColumn('broken_equipment_id');
            $table->dropColumn('status');
        });
    }
};
