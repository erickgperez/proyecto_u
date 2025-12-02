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
        Schema::create('academico.inscritos', function (Blueprint $table) {
            $table->id();
            $table->comment('Relación para definir asignaturas inscritas por el estudiante');

            $table->foreignId('imparte_id')->comment('Asignatura impartida');
            $table->foreign('imparte_id')->references('id')->on('academico.imparte')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('expediente_id')->comment('Id del expediente');
            $table->foreign('expediente_id')->references('id')->on('academico.expediente')->onDelete('RESTRICT')->onUpdate('CASCADE');


            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academico.inscritos');
    }
};
