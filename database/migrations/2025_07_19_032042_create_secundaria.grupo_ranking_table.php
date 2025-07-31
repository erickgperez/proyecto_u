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
        Schema::create('secundaria.grupo_ranking', function (Blueprint $table) {
            $table->id();
            $table->comment('Permite agrupar las carreras de bachillerato que deben ser comparadas entre ellas para obtener un ranking de notas. Por ejemplo, para comparar las notas de estudiantes que pertenecen a bachilleratos del área de turismo');
            $table->string('codigo', length: 10)->unique();
            $table->string('descripcion', length: 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secundaria.grupo_ranking');
    }
};
