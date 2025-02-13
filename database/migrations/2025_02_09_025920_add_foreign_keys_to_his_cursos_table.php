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
        Schema::table('his_cursos', function (Blueprint $table) {
            $table->foreign(['curso_id'], 'fk_his_cursos_curso')->references(['id'])->on('cursos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['estudiante_id'], 'fk_his_cursos_estudiante')->references(['id'])->on('estudiantes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('his_cursos', function (Blueprint $table) {
            $table->dropForeign('fk_his_cursos_curso');
            $table->dropForeign('fk_his_cursos_estudiante');
        });
    }
};
