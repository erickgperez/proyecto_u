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

            $table->string('descripcion', length: 255)->nullable();
            $table->boolean('activo')->default(true);

            $table->foreignId('tipo_flujo_id');
            $table->foreign('tipo_flujo_id')->references('id')->on('workflow.tipo_flujo')->onDelete('CASCADE')->onUpdate('CASCADE');

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
