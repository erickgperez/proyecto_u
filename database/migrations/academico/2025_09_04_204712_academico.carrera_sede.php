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
        Schema::create('academico.carrera_sede', function (Blueprint $table) {
            $table->id();
            $table->comment('Relación para definir las carreras que se imparten en una sede');
            $table->integer('cupo')->nullable()->comment('El cupo que tiene asignado la carrera en la sede');

            $table->foreignId('carrera_id')->comment('Id de la carrera que se relaciona con la sede');
            $table->foreign('carrera_id')->references('id')->on('plan_estudio.carrera')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('sede_id')->comment('Id de la sede en la que se imparte la carrera');
            $table->foreign('sede_id')->references('id')->on('academico.sede')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.carrera_sede');
    }
};
