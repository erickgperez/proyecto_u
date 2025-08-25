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

            $table->comment('Permite crear calendarizaciones para luego asignar a procesos');
            $table->string('codigo', length: 20)->unique();
            $table->string('descripcion', length: 50)->nullable();

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
