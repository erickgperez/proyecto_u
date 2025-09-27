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
        Schema::create('workflow.flujo_modelo', function (Blueprint $table) {
            $table->id();

            $table->comment('Asignación del flujo a utilizar en un modelo de proceso');

            $table->foreignId('flujo_id');
            $table->foreign('flujo_id')->references('id')->on('workflow.flujo')->onDelete('CASCADE')->onUpdate('CASCADE');
            // relación muchos a uno polimórfica (el registro padre puede ser de secundaria.carrera o plan_estudio.carrera)
            $table->unsignedBigInteger('modelo_id')->nullable()->comment('Id del modelo de proceso asignado al flujo');
            $table->string('modelo_type', length: 255)->nullable()->comment('Nombre del modelo donde se encuentra el id');

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
        Schema::dropIfExists('workflow.flujo_modelo');
    }
};
