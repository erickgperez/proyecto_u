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
        Schema::create('secundaria.institucion', function (Blueprint $table) {
            $table->id();
            $table->comment('Instituciones de educación media');
            $table->string('codigo', length: 20)->unique();
            $table->string('nombre', length: 255);
            $table->string('direccion', length: 255)->nullable();
            $table->foreignId('sector_id');
            $table->foreignId('distrito_id');
            $table->foreign('sector_id')->references('id')->on('secundaria.sector')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreign('distrito_id')->references('id')->on('distrito')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secundaria.instituciones');
    }
};
