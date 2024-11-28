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
        Schema::table('broken_equipments', function (Blueprint $table) {
            $table->text('image')->nullable();
            $table->dateTime('return_date')->nullable(true)->change();
            $table->text('report')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('broken_equipments', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dateTime('return_date')->nullable(false)->change();
            $table->dropColumn('report');
        });
    }
};
