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
        Schema::create('academico.calificacion', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Registro de la configuración de evaluaciones de las asignaturas');

            $table->decimal('calificacion', 4, 1)->nullable()->comment('Calificación de la evaluación');

            $table->foreignId('evaluacion_id')->comment('Id de la evaluación');
            $table->foreign('evaluacion_id')->references('id')->on('academico.evaluacion')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('inscrito_id')->comment('Id del estudiante inscrito');
            $table->foreign('inscrito_id')->references('id')->on('academico.inscritos')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.calificacion');
    }
};
