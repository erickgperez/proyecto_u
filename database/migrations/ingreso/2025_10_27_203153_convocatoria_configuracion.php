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
        Schema::create('ingreso.convocatoria_configuracion', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Los parámetros de configuración de la convocatoria');

            $table->tinyInteger('cuota_sector_publico')->nullable()->unsigned()->comment('Porcentaje de cupo asignado al sector público');
            $table->timestamp('fecha_publicacion_resultados')->nullable()->comment('Fecha a partir de la cual los aspirantes podrán visualizar si fueron seleccionados');
            $table->timestamp('fecha_inicio_recepcion_solicitudes')->nullable()->comment('Fecha en los aspirantes pueden empezar a crear solicitudes de ingreso');
            $table->timestamp('fecha_fin_recepcion_solicitudes')->nullable()->comment('Fecha de finalización de recepción de solicitudes');

            $table->foreignId('prueba_bachillerato_id')->nullable()->comment('Identificar el nombre de la prueba de los egresados de educación media');
            $table->foreign('prueba_bachillerato_id')->references('id')->on('secundaria.prueba_bachillerato')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('convocatoria_id')->nullable()->comment('Convocatoria a la que pertenece la configuración');
            $table->foreign('convocatoria_id')->references('id')->on('ingreso.convocatoria')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('ingreso.convocatoria_configuracion');
    }
};
