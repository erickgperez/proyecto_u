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
        Schema::create('workflow.solicitud', function (Blueprint $table) {
            $table->id();

            $table->comment('Solicitudes realizadas por la persona');

            $table->text('comentario')->nullable()->comment('Comentario de la etapa de la solicitud');

            $table->foreignId('flujo_modelo_id')->comment('Para identificar el proceso y el flujo que se ejecutará');
            $table->foreign('flujo_modelo_id')->references('id')->on('workflow.flujo_modelo')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('etapa_id')->comment('Etapa actual en que se encuentra la solicitud');
            $table->foreign('etapa_id')->references('id')->on('workflow.etapa')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('persona_id')->comment('Persona que está realizando la solicitud');
            $table->foreign('persona_id')->references('id')->on('public.persona')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('rol_id')->comment('Rol con el cual la persona está realizando la solicitud');
            $table->foreign('rol_id')->references('id')->on('public.roles')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('estado_id')->comment('Estado de la solicitud');
            $table->foreign('estado_id')->references('id')->on('workflow.estado')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('workflow.solicitud');
    }
};
