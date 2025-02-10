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
        Schema::create('his_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->index('fk_his_user_usuario');
            $table->string('nombre_usuario', 500);
            $table->timestamp('fecha_cambio')->useCurrentOnUpdate()->useCurrent();
            $table->string('campo_modificado', 500);
            $table->string('valor_antes', 500);
            $table->string('valor_despues', 500);
            $table->string('descripcion_cambio', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('his_user');
    }
};
