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
        Schema::create('profesores_cursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profesor_id')->index('fk_profesores_cursos_profesor');
            $table->unsignedBigInteger('curso_id')->index('fk_profesores_cursos_curso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesores_cursos');
    }
};
