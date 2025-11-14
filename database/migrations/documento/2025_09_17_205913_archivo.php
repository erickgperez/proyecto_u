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
        Schema::create('documento.archivo', function (Blueprint $table) {
            $table->id();
            $table->comment('Guarda los diferentes documentos');
            $table->string('nombre_original', length: 255)->comment('Nombre del archivo original');
            $table->string('tipo', length: 100)->comment('Se utilizará para guardar el tipo del documento, tomado de la extensión del archivo');
            $table->string('descripcion', length: 255)->comment('Descripción o comentario del archivo');
            $table->text('ruta')->nullable()->comment('Ruta donde está guardado el archivo');

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
        Schema::dropIfExists('documento.archivo');
    }
};
