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
        Schema::create('academico.evaluacion', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Registro de la configuración de evaluaciones de las asignaturas');

            $table->string('codigo', length: 10)->comment('Código de la evaluación');
            $table->string('descripcion', length: 255)->comment('Descripción de la evaluación');
            $table->date('fecha')->nullable()->comment('Fecha de la evaluación');
            $table->date('fecha_limite_ingreso_nota')->nullable()->comment('Fecha límite de ingreso de notas');
            $table->decimal('porcentaje', 5, 2)->comment('Porcentaje de la evaluación');

            $table->foreignId('oferta_id')->comment('Id de la oferta académica');
            $table->foreign('oferta_id')->references('id')->on('academico.oferta')->onDelete('CASCADE')->onUpdate('CASCADE');
            //$table->foreignId('tipo_evaluacion_id')->comment('Id del tipo de evaluación');
            //$table->foreign('tipo_evaluacion_id')->references('id')->on('academico.tipo_evaluacion')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.evaluacion');
    }
};
