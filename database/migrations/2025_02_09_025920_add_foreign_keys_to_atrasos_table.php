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
        Schema::table('atrasos', function (Blueprint $table) {
            $table->foreign(['estudiante_id'], 'fk_atrasos_estudiantes')->references(['id'])->on('estudiantes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['inspector_id'], 'fk_atrasos_inspector')->references(['id'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atrasos', function (Blueprint $table) {
            $table->dropForeign('fk_atrasos_estudiantes');
            $table->dropForeign('fk_atrasos_inspector');
        });
    }
};
