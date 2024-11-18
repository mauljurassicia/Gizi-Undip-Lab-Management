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
        Schema::table('scheduleables', function (Blueprint $table) {
            $table->index('schedule_id');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduleables', function (Blueprint $table) {
            $table->dropIndex(['schedule_id']);
            $table->dropForeign(['schedule_id']);
        });
    }
};
