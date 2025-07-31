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
            $table->dateTime('fecha_envio_correo')->comment('Fecha en que se envía el correo de invitación');
            $table->dateTime('fecha_aceptacion')->comment('Fecha en que el estudiante acepta la invitación y realiza su registro como aspirante');
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
