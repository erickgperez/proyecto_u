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
            $table->uuid('uuid')->unique()->after('id');

            $table->comment('Transiciones entre etapas');

            $table->string('codigo', length: 100);
            $table->string('nombre', length: 255);

            $table->foreignId('flujo_id')->comment('Flujo o proceso al que pertenece la transición');
            $table->foreign('flujo_id')->references('id')->on('workflow.etapa')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('etapa_origen_id')->comment('Etapa antes de realizar la transición');
            $table->foreign('etapa_origen_id')->references('id')->on('workflow.etapa')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('estado_origen_id')->comment('Estado que se tendrá la solicitud en la etapa origen, antes de realizar la transción');
            $table->foreign('estado_origen_id')->references('id')->on('workflow.estado')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('etapa_destino_id')->comment('Etapa en la que quedará la solicitud después de aplicar la transición');
            $table->foreign('etapa_destino_id')->references('id')->on('workflow.etapa')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('estado_destino_id')->comment('Estado que se tendrá la solicitud en la etapa destino');
            $table->foreign('estado_destino_id')->references('id')->on('workflow.estado')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('workflow.transicion');
    }
};
