<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtrasosTable extends Migration
{
    public function up()
    {
        Schema::create('atrasos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')
                  ->constrained('estudiantes')
                  ->onDelete('cascade');
            $table->timestamp('fecha_atraso')->useCurrent();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreignId('inspector_id')
                ->constrained('usuarios')
                ->onDelete('cascade');
            $table->string('razon', 500);
            $table->binary('evidencia')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('atrasos');
    }
}
