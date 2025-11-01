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
        Schema::create('ingreso.convocatoria', function (Blueprint $table) {
            $table->id();
            $table->comment('Las convocatorias que se realizarán en ingreso universitario');
            $table->string('nombre', length: 100)->comment('Nombre identificador de la convocatoria');
            $table->string('descripcion', length: 255)->nullable()->comment('Texto detallado de la convocatoria');
            $table->timestamp('fecha')->nullable()->comment('Fecha en que se lanzará la convocatoria');
            $table->text('cuerpo_mensaje')->nullable()->comment('Texto que se mostrará en las invitaciones que se enviarán por correo ');
            $table->string('afiche', length: 255)->nullable()->comment('Archivo del afiche informativo de la convocatoria, se adjuntará en la invitación enviada por correo');
            $table->foreignId('flujo_id')->comment('Flujo de proceso que seguirás las solicitudes de ingreso de esta convocatoria');
            $table->foreign('flujo_id')->references('id')->on('workflow.flujo')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('calendarizacion_id')->nullable()->comment('Calendario de eventos de la convocatoria');
            $table->foreign('calendarizacion_id')->references('id')->on('public.calendarizacion')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('ingreso.convocatoria');
    }
};
