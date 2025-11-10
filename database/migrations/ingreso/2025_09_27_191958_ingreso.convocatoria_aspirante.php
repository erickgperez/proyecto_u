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
        Schema::create('ingreso.convocatoria_aspirante', function (Blueprint $table) {
            $table->id();

            $table->comment('Relación de muchos a muchos entre convocatoria y aspirante');

            $table->foreignId('convocatoria_id')->comment('Id de la convocatoria a la que está asignado el aspirante');
            $table->foreign('convocatoria_id')->references('id')->on('ingreso.convocatoria')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('aspirante_id')->comment('Id del aspirante que está asignado a la convocatoria');
            $table->foreign('aspirante_id')->references('id')->on('ingreso.aspirante')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->boolean('seleccionado')->default(false)->comment('Indica si el aspirante ha sido seleccionado como estudiante');
            $table->dateTime('fecha_notificacion_seleccion')->nullable()->comment('Fecha en que se envía el correo de notificación de selección');

            $table->foreignId('solicitud_carrera_sede_id')->comment('Carrera sede en que fue seleccionada')->nullable();
            $table->foreign('solicitud_carrera_sede_id')->references('id')->on('workflow.solicitud_carrera_sede')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('ingreso.convocatoria_aspirante');
    }
};
