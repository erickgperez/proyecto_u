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
            $table->string('nombre', length: 50)->comment('Nombre descriptivo del evento');
            $table->string('descripcion', length: 255)->nullable()->comment('Detalle del evento');
            $table->text('indicaciones')->nullable()->comment('Si el evento requiere indicaciones adicionales');
            $table->boolean('completado')->default(false)->comment('Indica si el evento se ha completado');
            $table->foreignId('calendarizacion_id');
            $table->foreign('calendarizacion_id')->references('id')->on('public.calendarizacion')->onDelete('CASCADE')->onUpdate('CASCADE');
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
