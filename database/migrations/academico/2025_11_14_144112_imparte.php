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
        Schema::create('academico.imparte', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Para asignar el docente que impartida la unidad académica ofertada en una sede');

            $table->boolean('ofertada')->nullable()->default(true)->comment('Indicará que no se debe ofertar la unidad academica, en esa carrera/sede');
            $table->tinyInteger('cupo')->nullable()->default(0)->comment('El cupo total de la unidad academica, en la carrera/sede');

            $table->foreignId('carrera_sede_id')->comment('la carrera y sede donde se impartirá');
            $table->foreign('carrera_sede_id')->references('id')->on('academico.carrera_sede')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('oferta_id')->comment('Id de la oferta académica');
            $table->foreign('oferta_id')->references('id')->on('academico.oferta')->onDelete('CASCADE')->onUpdate('CASCADE');

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
