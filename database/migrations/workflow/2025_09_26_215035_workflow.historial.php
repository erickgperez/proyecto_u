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
        Schema::create('workflow.historial', function (Blueprint $table) {
            $table->id();

            $table->comment('Historial de las etapas por las que ha pasado la solicitud');

            $table->text('comentario')->nullable()->comment('Comentario de la etapa de la solicitud');

            $table->foreignId('solicitud_id')->comment('Solicitud a la que pertenece el historial');
            $table->foreign('solicitud_id')->references('id')->on('workflow.solicitud')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('etapa_id')->comment('Etapa actual en que se encuentra la solicitud');
            $table->foreign('etapa_id')->references('id')->on('workflow.etapa')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('workflow.historial');
    }
};
