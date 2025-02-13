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
        Schema::table('cont_atrasos', function (Blueprint $table) {
            $table->unsignedBigInteger('estudiante_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cont_atrasos', function (Blueprint $table) {
            $table->integer('estudiante_id')->change();
        });
    }
};
