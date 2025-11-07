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
        Schema::create('plan_estudio.unidad_academica', function (Blueprint $table) {
            $table->id();

            $table->comment('Las unidades académicas que componen las carreras');
            $table->string('codigo', length: 50)->unique()->comment('Código que identifica a la unidad académica');
            $table->text('nombre')->comment('Nombre de la unidad de estudio');
            $table->float('creditos', 5, 2)->nullable()->comment('Cantidad de créditos que otorga la unidad de estudio al aprobarla');
            $table->foreignId('tipo_unidad_academica_id')->comment('Identificador del tipo de unidad académica');
            $table->foreign('tipo_unidad_academica_id')->references('id')->on('plan_estudio.tipo_unidad_academica')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('plan_estudio.unidad_academica');
    }
};
