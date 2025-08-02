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
        Schema::create('plan_estudio.grado', function (Blueprint $table) {
            $table->id();
            $table->comment('Contine el grado otorgado por un tipo de carrera');
            $table->string('codigo', length: 15)->unique();
            $table->string('descripcion_masculino', length: 100)->comment('grado que se le otorga a un hombre por finalizar la carrera.Ej.: Técnico');
            $table->string('descripcion_femenino', length: 100)->comment('grado que se le otorga a una mujer por finalizar la carrera.Ej.: Técnica');;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_estudio.grado');
    }
};
