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
        Schema::create('secundaria.invitacion', function (Blueprint $table) {
            $table->id();
            $table->comment('Invitaciones enviadas a los estudiantes de bachillerato');
            $table->string('nie', length: 15)->unique()->comment('Número de identificación de estudiante');
            $table->string('codigo', length: 15)->nullable()->comment('Código de invitación, se usará junto al nie para que el estudiante se registre');
            $table->boolean('invitado')->default(true);
            $table->dateTime('fecha_envio_correo')->nullable()->comment('Fecha en que se envía el correo de invitación');
            $table->dateTime('fecha_aceptacion')->nullable()->comment('Fecha en que el estudiante acepta la invitación y realiza su registro como aspirante');
            $table->foreignId('convocatoria_id')->comment('Convocatoria a la que se hace la invitación');
            $table->foreign('convocatoria_id')->references('id')->on('ingreso.convocatoria')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('secundaria.invitacion');
    }
};
