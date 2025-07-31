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
        Schema::create('secundaria.estudio', function (Blueprint $table) {
            $table->id();
            $table->comment('Permite vincular a los estudiantes, la institución y la opción de bachillerato que están estudiando');
            $table->unsignedTinyInteger('anio');
            $table->decimal('nota_promocion', total: 6, places: 2)->nullable()->comment('Nota obtenida por el estudiante en la prueba AVANZO');
            $table->foreignId('estudiante_id');
            $table->foreignId('carrera_id')->comment('La opción de bachillerato que está cursando el estudiante');
            $table->foreignId('institucion_id')->comment('La institución a la que pertenece el estudiante');
            $table->foreign('estudiante_id')->references('id')->on('secundaria.estudiante')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign('carrera_id')->references('id')->on('secundaria.carrera')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign('institucion_id')->references('id')->on('secundaria.institucion')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secundaria.estudio');
    }
};
