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
        Schema::table('cursos', function (Blueprint $table) {
            $table->foreign(['grado_id'], 'fk_cursos_grados')->references(['id'])->on('grados')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['profesor_jefe_id'], 'fk_cursos_profesor_jefe')->references(['id'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropForeign('fk_cursos_grados');
            $table->dropForeign('fk_cursos_profesor_jefe');
        });
    }
};
