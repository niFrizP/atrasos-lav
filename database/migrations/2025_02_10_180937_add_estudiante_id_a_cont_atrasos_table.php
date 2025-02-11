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
            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cont_atrasos', function (Blueprint $table) {
            $table->dropForeign(['estudiante_id']);
            $table->dropColumn('estudiante_id');
        });
    }
};
