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
        Schema::create('academico.asignado', function (Blueprint $table) {
            $table->id();
            $table->comment('Especifica en qué carreras/sedes está asignado un docente');

            $table->boolean('principal')->nullable()->default(false)->comment('Indica que está destacada principalmente en esta sede/carrera');

            $table->foreignId('carrera_sede_id')->comment('la carrera y sede donde se impartirá');
            $table->foreign('carrera_sede_id')->references('id')->on('academico.carrera_sede')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('docente_id')->nullable()->comment('Id del docente encargado');
            $table->foreign('docente_id')->references('id')->on('academico.docente')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.imparte');
    }
};
