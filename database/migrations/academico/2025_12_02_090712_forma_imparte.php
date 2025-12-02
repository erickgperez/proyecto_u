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
        Schema::create('academico.forma_imparte', function (Blueprint $table) {
            $table->id();
            $table->comment('Relación para definir forma en que está relacionado un docente al impartir una asignatura');

            $table->string('codigo', length: 50)->unique()->comment('Código de identificación de la forma de impartir');
            $table->string('descripcion', length: 150)->nullable()->comment('Nombre descriptivo de la forma de impartir');

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
        Schema::dropIfExists('academico.forma_imparte');
    }
};
