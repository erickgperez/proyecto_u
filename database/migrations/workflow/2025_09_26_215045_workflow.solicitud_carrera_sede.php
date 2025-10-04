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
        Schema::create('workflow.solicitud_carrera_sede', function (Blueprint $table) {
            $table->id();

            $table->comment('La carrera y sede para la cual se está realizando la solicitud');

            $table->foreignId('solicitud_id')->comment('Relación con solicitud');
            $table->foreign('solicitud_id')->references('id')->on('workflow.solicitud')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('tipo_carrera_sede_solicitud_id')->comment('Carrera sede en que se realiza la solicitud');
            $table->foreign('tipo_carrera_sede_solicitud_id')->references('id')->on('workflow.tipo_carrera_sede_solicitud')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('carrera_sede_id')->comment('Carrera sede en que se realiza la solicitud');
            $table->foreign('carrera_sede_id')->references('id')->on('academico.carrera_sede')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('workflow.solicitud_carrera_sede');
    }
};
