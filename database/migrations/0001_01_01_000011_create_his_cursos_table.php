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
        Schema::create('his_cursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id')->index('fk_his_cursos_estudiante');
            $table->unsignedBigInteger('curso_id')->index('fk_his_cursos_curso');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->string('motivo_cambio', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('his_cursos');
    }
};
