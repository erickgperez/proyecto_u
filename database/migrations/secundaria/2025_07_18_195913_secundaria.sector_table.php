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
        Schema::create('secundaria.sector', function (Blueprint $table) {
            $table->id();
            $table->comment('Sector al que pertenece la institución educativa: Público(PU) o Privado(PR)');
            $table->string('codigo', length: 10)->unique();
            $table->string('descripcion', length: 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secundaria.sector');
    }
};
