<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiantesTable extends Migration
{
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nomape', 500);
            $table->string('rut', 15)->unique();
            $table->string('telefono', 9)->nullable();
            $table->string('correo', 500)->nullable();
            $table->binary('qr')->nullable();
            $table->foreignId('curso_id')->nullable()->constrained('cursos')->nullOnDelete();
            $table->binary('fotografia')->nullable();
            $table->foreignId('estado_id')
                ->nullable()
                ->default(1)
                ->constrained('estados')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estudiantes');
    }
}
