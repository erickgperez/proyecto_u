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
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin')->nullable();
            $table->string('nombre', length: 50);
            $table->string('descripcion', length: 255)->nullable();
            $table->text('indicaciones')->nullable();
            $table->boolean('completado')->default(false);
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
