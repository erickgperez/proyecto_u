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
        Schema::create('pais', function (Blueprint $table) {
            $table->id();
            $table->comment('Catálogo de paises');
            $table->string('nombre', length: 200);
            $table->string('name', length: 200);
            $table->string('iso2', length: 2)->unique();
            $table->string('iso3', length: 3)->unique();
            $table->string('continente', length: 100)->nullable();

            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pais');
    }
};
