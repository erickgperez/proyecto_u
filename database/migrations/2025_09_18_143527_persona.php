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
        Schema::create('persona', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Almacena la información general de las personas (aspirantes, estudiantes, docentes y otros)');
            $table->string('primer_nombre', length: 100);
            $table->string('segundo_nombre', length: 100)->nullable();
            $table->string('tercer_nombre', length: 100)->nullable();
            $table->string('primer_apellido', length: 100);
            $table->string('segundo_apellido', length: 100)->nullable();
            $table->string('tercer_apellido', length: 100)->nullable();
            $table->timestamp('fecha_nacimiento')->nullable();
            $table->foreignId('sexo_id')->nullable();
            $table->foreign('sexo_id')->references('id')->on('public.sexo')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->boolean('permitir_editar')->default('false');

            $table->unsignedBigInteger('created_by')->nullable()->comment('Usuario que creó el registro');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('Usuario que realizó la última actualización del registro');
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
        Schema::dropIfExists('persona');
    }
};
