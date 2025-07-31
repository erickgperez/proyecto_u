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
        Schema::create('sexo', function (Blueprint $table) {
            $table->id();
            $table->comment('Catálogo de sexo de la persona');
            $table->string('codigo', length: 5)->unique()->comment('Código que representa el sexo');
            $table->string('descripcion', length: 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sexo');
    }
};
