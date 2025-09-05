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
        Schema::create('academico.carrera_sede', function (Blueprint $table) {
            $table->id();
            $table->comment('Relación para definir las carreras que se imparten en una sede');

            $table->foreignId('carrera_id');
            $table->foreign('carrera_id')->references('id')->on('plan_estudio.carrera')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('sede_id');
            $table->foreign('sede_id')->references('id')->on('academico.sede')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academico.carrera_sede');
    }
};
