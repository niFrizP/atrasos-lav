<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nomape', 350);
            $table->string('rut', 15)->unique();
            $table->string('telefono', 9)->nullable();
            $table->string('correo', 350)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 500);
            $table->rememberToken();
            $table->binary('qr')->nullable();
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade'); // foreign key
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
