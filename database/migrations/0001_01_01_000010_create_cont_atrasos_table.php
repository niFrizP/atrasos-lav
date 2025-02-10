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
        Schema::create('cont_atrasos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id')->index('fk_cont_atrasos_estudiantes');
            $table->integer('total_atrasos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cont_atrasos');
    }
};
