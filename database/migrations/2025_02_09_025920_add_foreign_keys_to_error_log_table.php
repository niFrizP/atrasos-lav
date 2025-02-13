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
        Schema::table('error_log', function (Blueprint $table) {
            $table->foreign(['usuario_id'], 'fk_error_log_usuario')->references(['id'])->on('usuarios')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('error_log', function (Blueprint $table) {
            $table->dropForeign('fk_error_log_usuario');
        });
    }
};
