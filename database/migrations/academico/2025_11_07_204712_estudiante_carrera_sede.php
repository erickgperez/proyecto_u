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
        Schema::create('academico.estudiante_carrera_sede', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Relación para definir las carreras que cursa el estudiante');
            $table->timestamp('fecha_inicio')->nullable()->comment('Fecha en que empezó la carrera');

            $table->foreignId('carrera_sede_id')->comment('Sede y carrera que cursa el estudiante');
            $table->foreign('carrera_sede_id')->references('id')->on('academico.carrera_sede')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('estudiante_id')->comment('Id del estudiante ');
            $table->foreign('estudiante_id')->references('id')->on('academico.estudiante')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('estado_id')->comment('Id del estado del curso de la carrera');
            $table->foreign('estado_id')->references('id')->on('academico.estado')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.estudiante_carrera_sede');
    }
};
