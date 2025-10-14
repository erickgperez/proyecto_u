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
        Schema::create('ingreso.aspirante', function (Blueprint $table) {
            $table->id();

            $table->comment('Tabla de aspirantes a una convocatoria de ingreso');
            $table->string('nie', length: 30)->nullable()->comment('Número de identificación de estudiante');
            $table->float('calificacion_bachillerato', 5, 2)->nullable()->comment('Calificación obtenida en la prueba que se realiza en educación media');
            $table->foreignId('persona_id')->nullable();
            $table->foreign('persona_id')->references('id')->on('public.persona')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso.aspirante');
    }
};
