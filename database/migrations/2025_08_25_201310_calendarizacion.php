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
        Schema::create('calendarizacion', function (Blueprint $table) {
            $table->id();

            $table->comment('Permite crear calendarizaciones para asignar a procesos');
            $table->string('codigo', length: 50)->unique()->comment('Código de la calendarización, normalmente llevará el código del proceso al que está asociado el calendario');
            $table->string('descripcion', length: 100)->nullable()->comment('Texto, por si se necesita ampliar la descripción del calendario');

            $table->foreignId('tipo_calendarizacion_id')->comment('Tipo de calendario, se usará para identificar el proceso en que se usará el calendario');
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
        Schema::dropIfExists('calendarizacion');
    }
};
