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
        Schema::create('tipo_evento', function (Blueprint $table) {
            $table->id();

            $table->comment('Permite catalogar los eventos según el proceso en que se vayan a utilizar');
            $table->string('codigo', length: 100)->unique()->comment('Código para identificar el tipo de evento');
            $table->string('descripcion', length: 255)->unique()->comment('Texto descriptivo del tipo de evento');
            $table->foreignId('tipo_calendarizacion_id')->comment('Para identificar el proceso o tipo de calendario en que se puede usar el evento');
            $table->foreign('tipo_calendarizacion_id')->references('id')->on('public.tipo_calendarizacion')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('tipo_evento');
    }
};
