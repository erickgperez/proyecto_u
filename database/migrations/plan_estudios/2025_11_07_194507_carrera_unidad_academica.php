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
        Schema::create('plan_estudio.carrera_unidad_academica', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Tabla para relacionar las unidades académicas que pertenecen a una carrera');
            $table->tinyInteger('semestre')->comment('Semestre en que se imparte la unidad academica');
            $table->boolean('obligatoria')->default(true)->comment('Indica si la unidad de estudio es obligatoria en la carrera');
            $table->float('requisito_creditos', 4, 1)->nullable()->comment('Créditos otorgados por la unidad académica');

            $table->foreignId('area_id')->nullable()->comment('Área a la que pertenece la unidad académica');
            $table->foreign('area_id')->references('id')->on('plan_estudio.area')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('carrera_id');
            $table->foreign('carrera_id')->references('id')->on('plan_estudio.carrera')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('unidad_academica_id');
            $table->foreign('unidad_academica_id')->references('id')->on('plan_estudio.unidad_academica')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('plan_estudio.carrera_unidad_academica');
    }
};
