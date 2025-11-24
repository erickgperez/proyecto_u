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
        Schema::create('academico.expediente', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Tabla para almacenar el expediente académico del estudiante');

            $table->tinyInteger('matricula')->comment('Matrícula en que cursa la asignatura');
            $table->decimal('calificacion', 5, 2)->nullable()->comment('Calificación obtenida');

            $table->foreignId('estudiante_id')->comment('Identificador del estudiante');
            $table->foreign('estudiante_id')->references('id')->on('academico.estudiante')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('carrera_unidad_academica_id')->comment('Identificador de la asignatura');
            $table->foreign('carrera_unidad_academica_id')->references('id')->on('plan_estudio.carrera_unidad_academica')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('estado_id')->comment('Id del estado de la asignatura');
            $table->foreign('estado_id')->references('id')->on('academico.estado')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('semestre_id')->comment('Id del semestre en que se cursó la asignatura');
            $table->foreign('semestre_id')->references('id')->on('academico.semestre')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('tipo_curso_id')->comment('Identificador de la forma en que cursó la asignatura');
            $table->foreign('tipo_curso_id')->references('id')->on('academico.tipo_curso')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.expediente');
    }
};
