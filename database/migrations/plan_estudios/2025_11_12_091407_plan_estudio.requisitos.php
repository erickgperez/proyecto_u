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
        Schema::create('plan_estudio.requisitos', function (Blueprint $table) {
            $table->id();
            $table->comment('Tabla definir los requisitos de las unidades académicas');

            $table->foreignId('unidad_academica_id');
            $table->foreign('unidad_academica_id')->references('id')->on('plan_estudio.unidad_academica')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('unidad_academica_requisito_id');
            $table->foreign('unidad_academica_requisito_id')->references('id')->on('plan_estudio.unidad_academica')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('tipo_requisito_id');
            $table->foreign('tipo_requisito_id')->references('id')->on('plan_estudio.tipo_requisito')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('plan_estudio.requisitos');
    }
};
