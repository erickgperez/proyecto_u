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
        Schema::create('secundaria.carrera', function (Blueprint $table) {
            $table->id();
            $table->comment('Las diferentes opciones de bachillerato');
            $table->string('codigo', length: 10)->nullable()->unique();
            $table->string('descripcion', length: 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secundaria.carrera');
    }
};
