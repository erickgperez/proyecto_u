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
        Schema::create('documento.tipo', function (Blueprint $table) {
            $table->id();
            $table->comment('Catálogo de tipos de documentos');
            $table->string('codigo', length: 50)->unique();
            $table->string('descripcion', length: 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento.tipo');
    }
};
