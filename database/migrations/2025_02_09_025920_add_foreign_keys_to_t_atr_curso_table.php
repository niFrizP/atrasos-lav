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
        Schema::table('t_atr_curso', function (Blueprint $table) {
            $table->foreign(['curso_id'], 'fk_t_atr_curso_curso')->references(['id'])->on('cursos')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_atr_curso', function (Blueprint $table) {
            $table->dropForeign('fk_t_atr_curso_curso');
        });
    }
};
