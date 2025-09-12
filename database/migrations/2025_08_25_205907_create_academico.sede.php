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
        Schema::create('academico.sede', function (Blueprint $table) {
            $table->id();
            $table->comment('Registro de las sedes');
            $table->string('codigo', length: 20)->unique();
            $table->string('nombre', length: 255)->nullable();
            $table->foreignId('distrito_id')->comment('Distrito donde está ubicada la sede');
            $table->foreign('distrito_id')->references('id')->on('distrito');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academico.sede');
    }
};
