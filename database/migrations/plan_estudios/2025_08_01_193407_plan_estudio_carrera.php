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
        Schema::create('plan_estudio.carrera', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', length: 30)->unique()->comment('Código asignado a la carrera');
            $table->text('nombre')->comment('Nombre de la carrera');
            $table->foreignId('certificacion_de')->nullable()->comment('Una carrera puede estar compuesta de certificaciones, las certificaciones también serán guardadas como una carrera');
            $table->foreign('certificacion_de')->references('id')->on('plan_estudio.carrera')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('tipo_carrera_id');
            $table->foreign('tipo_carrera_id')->references('id')->on('plan_estudio.tipo_carrera')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('plan_estudio.carrera');
    }
};
