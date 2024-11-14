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
        Schema::table('schedules', function (Blueprint $table) {

            $table->enum('status', ["approved", "rejected", "pending"])->default('pending')->after('end_schedule');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {

            $table->dropColumn('status');

            $table->dropForeign('schedules_course_id_foreign');
            $table->dropForeign('schedules_room_id_foreign');
        });
    }
};
