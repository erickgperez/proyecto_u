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
        Schema::create('documento.documento', function (Blueprint $table) {
            $table->id();
            $table->comment('Guarda la información de los documentos subidos al sistema');
            $table->string('numero', length: 100);
            $table->timestamp('fecha_emision')->nullable();
            $table->timestamp('fecha_expiracion')->nullable();
            $table->boolean('revisado');
            $table->foreignId('tipo_id')->nullable()->comment('Tipo del documento');
            $table->foreign('tipo_id')->references('id')->on('documento.tipo')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('documento.documento');
    }
};
