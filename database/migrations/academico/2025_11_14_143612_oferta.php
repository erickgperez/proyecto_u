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
        Schema::create('academico.oferta', function (Blueprint $table) {
            $table->id();
            $table->comment('Oferta académica del semestre');

            $table->foreignId('carrera_unidad_academica_id')->comment('la unidad académica que se ofertará');
            $table->foreign('carrera_unidad_academica_id')->references('id')->on('plan_estudio.carrera_unidad_academica')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('semestre_id')->comment('Id del semestre en que se está ofertando la unidad académica');
            $table->foreign('semestre_id')->references('id')->on('academico.semestre')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.oferta');
    }
};
