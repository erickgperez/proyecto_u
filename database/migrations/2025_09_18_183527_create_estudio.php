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
        Schema::create('estudio', function (Blueprint $table) {
            $table->id();
            $table->comment('Los estudios realizados por una persona');
            $table->string('nombre_titulo', length: 255)->comment('Nombre del título obtenido');
            $table->string('nombre_institucion', length: 255)->comment('Nombre de la institución donde se obtuvo (En el caso que no se tenga registrada en la base de datos)');
            $table->timestamp('fecha_finalizacion')->nullable();
            $table->foreignId('grado_id')->nullable()->comment('Relación para determinar el grado obtenido');
            $table->foreign('grado_id')->references('id')->on('plan_estudio.grado')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('persona');
    }
};
