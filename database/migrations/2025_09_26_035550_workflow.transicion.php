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
        Schema::create('workflow.transicion', function (Blueprint $table) {
            $table->id();

            $table->comment('Transiciones entre etapas');

            $table->string('nombre', length: 255);

            $table->foreignId('etapa_origen_id');
            $table->foreign('etapa_origen_id')->references('id')->on('workflow.etapa')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('etapa_destino_id');
            $table->foreign('etapa_destino_id')->references('id')->on('workflow.etapa')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow.transicion');
    }
};
