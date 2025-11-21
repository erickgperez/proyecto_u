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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Almacena los usuarios del sistema');
            $table->string('name')->comment('Nombre del usuario');
            $table->string('email')->unique()->comment('Dirección de correo que se utilizará para ingresar al sistema');
            $table->timestamp('email_verified_at')->nullable()->comment('Fecha en que se realizó el proceso de verificación de correo');
            $table->string('password')->comment('Clave del usuario');
            $table->rememberToken()->comment('Token asignado para recordar al usuario');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->comment('Tabla para la gestión de restauración de claves de usuario');
            $table->string('email')->primary()->comment('Dirección de correo del usuario');
            $table->string('token')->comment('Token para restarurar la clave');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->comment('Lleva el registro de las sesiones de usuario');
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable()->comment('Dirección IP desde donde accede el usuario');
            $table->text('user_agent')->nullable()->comment('Forma de conexión, si lo hace vía web o desde consola de comandos');
            $table->longText('payload')->comment('identificador de la sesión');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
