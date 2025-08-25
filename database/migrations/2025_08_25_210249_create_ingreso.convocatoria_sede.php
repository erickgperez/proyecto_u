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
        Schema::create('ingreso.convocatoria_sede', function (Blueprint $table) {
            $table->id();
            $table->comment('Relación para definir las carreras para las cuales se hará la convocatoria');

            $table->foreignId('convocatoria_id');
            $table->foreign('convocatoria_id')->references('id')->on('ingreso.convocatoria')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('ingreso.convocatoria_sede');
    }
};
