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
        Schema::create('t_atr_curso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_id')->index('fk_t_atr_curso_curso');      
            $table->integer('total_atrasos');
            $table->timestamp('fecha_actualizacion')->useCurrentOnUpdate()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_atr_curso');
    }
};
