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
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Instituciones de educación media');
            $table->string('codigo', length: 20)->unique()->comment('Código que identifica a la institución');
            $table->string('nombre', length: 255)->comment('Nombre de la institución de bachillerato');
            $table->string('direccion', length: 255)->nullable();

            $table->foreign('sector_id')->references('id')->on('secundaria.sector')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('sector_id')->comment('Sector al que pertenece la institución');

            $table->foreignId('distrito_id')->comment('Distrito donde está ubicada la institución');
            $table->foreign('distrito_id')->references('id')->on('distrito')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('pais_id')->comment('País de la institución');
            $table->foreign('pais_id')->references('id')->on('distrito')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
