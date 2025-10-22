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
        Schema::create('workflow.tipo_carrera_sede_solicitud', function (Blueprint $table) {
            $table->id();

            $table->comment('Catálogo para identificar el papel de la carrera sede en la solicitud, si será carrera origen o carrera destino. Utilizado en los procesos como cambio de carrera. En procesos de ingreso, si la carrera es primera, segunda o tercera opción');

            $table->string('codigo', length: 50)->unique();
            $table->string('descripcion', length: 150)->nullable();

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
        Schema::dropIfExists('workflow.tipo_carrera_sede_solicitud');
    }
};
