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
        Schema::create('academico.estado', function (Blueprint $table) {
            $table->id();
            $table->comment('Clasificación de estados');
            $table->string('codigo', length: 50)->unique()->comment('Código del estado');
            $table->string('descripcion', length: 255)->nullable()->comment('Nombre descriptivo del estado');

            $table->foreignId('uso_estado_id')->comment('Id del ámbito en que se utilizará el estado');
            $table->foreign('uso_estado_id')->references('id')->on('academico.uso_estado')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.estado');
    }
};
