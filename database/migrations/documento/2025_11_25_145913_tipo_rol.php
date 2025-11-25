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
        Schema::create('documento.tipo_rol', function (Blueprint $table) {
            $table->id();
            $table->comment('Tabla intermedia de relación muchos a muchos entre tipo documento y rol');
            $table->foreignId('tipo_id')->comment('Tipo del documento');
            $table->foreign('tipo_id')->references('id')->on('documento.tipo')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('rol_id')->comment('Rol al que se le solicitará el tipo de documento');
            $table->foreign('rol_id')->references('id')->on('public.roles')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->unsignedBigInteger('created_by')->nullable()->comment('Usuario que creó el registro');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Usuario que realizó la última actualización del registro');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento.tipo_rol');
    }
};
