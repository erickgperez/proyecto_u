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
        Schema::create('workflow.tipo_flujo', function (Blueprint $table) {
            $table->id();

            $table->comment('Catálogo para tipos de flujo');

            $table->string('codigo', length: 50)->unique();
            $table->string('descripcion', length: 150)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow.tipo_flujo');
    }
};
