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
        Schema::create('ingreso.convocatoria', function (Blueprint $table) {
            $table->id();
            $table->comment('Las convocatorias que se realizarán en ingreso universitario');
            $table->string('descripcion', length: 255)->nullable();
            $table->text('cuerpo_mensaje')->nullable();
            $table->string('afiche', length: 255)->nullable();

            $table->foreignId('calendarizacion_id');
            $table->foreign('calendarizacion_id')->references('id')->on('public.calendarizacion')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso.convocatoria');
    }
};
