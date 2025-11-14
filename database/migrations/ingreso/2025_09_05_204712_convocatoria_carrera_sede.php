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
        Schema::create('ingreso.convocatoria_carrera_sede', function (Blueprint $table) {
            $table->id();
            $table->comment('Relación para definir las carreras (por sede) para las cuales se hará la convocatoria');

            $table->foreignId('convocatoria_id')->comment('Id de la convocatoria a la que se le asigna la carrera/sede');
            $table->foreign('convocatoria_id')->references('id')->on('ingreso.convocatoria')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('carrera_sede_id')->comment('Id de la carrera/sede');
            $table->foreign('carrera_sede_id')->references('id')->on('academico.carrera_sede')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('ingreso.convocatoria_carrera_sede');
    }
};
