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

            $table->foreignId('convocatoria_id');
            $table->foreign('convocatoria_id')->references('id')->on('ingreso.convocatoria')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('aspirante_id');
            $table->foreign('aspirante_id')->references('id')->on('ingreso.aspirante')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('carrera_sede_id')->comment('Carrera sede en que fue seleccionado')->nullable();
            $table->foreign('carrera_sede_id')->references('id')->on('wordkflow.solicitud_carrera_sede')->onDelete('CASCADE')->onUpdate('CASCADE');

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
