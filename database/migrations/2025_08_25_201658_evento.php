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
        Schema::create('evento', function (Blueprint $table) {
            $table->id();
            $table->comment('Eventos que se darán en una calendarización');
            $table->timestamp('fecha_inicio')->comment('Fecha en que inicia el evento');
            $table->timestamp('fecha_fin')->nullable()->comment('Fecha en que finaliza el evento');
            $table->string('nombre', length: 100)->nullable()->comment('Nombre descriptivo del evento');
            $table->text('indicaciones')->nullable()->comment('Si el evento requiere indicaciones adicionales');
            $table->boolean('completado')->default(false)->comment('Indica si el evento se ha completado');
            $table->foreignId('calendarizacion_id')->comment('Identificador del calendario al que pertenece el evento');
            $table->foreign('calendarizacion_id')->references('id')->on('public.calendarizacion')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('tipo_evento_id')->nullable()->comment('Tipo de evento');
            $table->foreign('tipo_evento_id')->references('id')->on('public.tipo_evento')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('evento');
    }
};
