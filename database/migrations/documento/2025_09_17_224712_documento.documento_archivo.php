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
        Schema::create('documento.documento_archivo', function (Blueprint $table) {
            $table->id();
            $table->comment('Relación de muchos a muchos entre documento y archivo');

            $table->foreignId('documento_id')->comment('Id del documento al que pertenece el archivo');
            $table->foreign('documento_id')->references('id')->on('documento.documento')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('archivo_id')->comment('Id del archivo');
            $table->foreign('archivo_id')->references('id')->on('documento.archivo')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento.documento_archivo');
    }
};
