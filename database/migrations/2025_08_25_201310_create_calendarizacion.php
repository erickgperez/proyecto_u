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
            $table->string('nombre', length: 200)->unique()->comment('Nombre de la calendarización, normalmente llevará el nombre del proceso al que está asociado el calendario');

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
