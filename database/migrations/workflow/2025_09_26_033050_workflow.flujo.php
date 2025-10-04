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
        Schema::create('workflow.flujo', function (Blueprint $table) {
            $table->id();

            $table->comment('Para la creación de los procesos que se realizarán');

            $table->string('codigo', length: 100);
            $table->string('nombre', length: 255);
            $table->boolean('activo')->default(true);

            $table->foreignId('tipo_flujo_id');
            $table->foreign('tipo_flujo_id')->references('id')->on('workflow.tipo_flujo')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('workflow.flujo');
    }
};
