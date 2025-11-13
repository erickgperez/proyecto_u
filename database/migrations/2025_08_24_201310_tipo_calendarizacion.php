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
        Schema::create('tipo_calendarizacion', function (Blueprint $table) {
            $table->id();

            $table->comment('Permite crear calendarizaciones para asignar a procesos');
            $table->string('codigo', length: 100)->unique()->comment('Código para identificar el proceso en que se usará un calendario');
            $table->string('descripcion', length: 255)->unique()->comment('Texto descriptivo del tipo de calendarización');

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
        Schema::dropIfExists('tipo_calendarizacion');
    }
};
