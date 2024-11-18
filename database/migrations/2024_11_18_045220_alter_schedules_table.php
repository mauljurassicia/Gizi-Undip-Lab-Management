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
            if(Schema::hasColumn('schedules', 'schedule_type')) {
                $table->dropColumn('schedule_type');
            }
            
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('schedule_type', ['weekly', 'monthly', 'onetime', 'series'])->default('onetime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            if(Schema::hasColumn('schedules', 'schedule_type')) {
                $table->dropColumn('schedule_type');
            }
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('schedule_type', ['weekly', 'monthly', 'onetime'])->default('onetime');
        });
    }
};
