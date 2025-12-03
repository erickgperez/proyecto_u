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
            $table->string('primer_nombre', length: 100)->comment('Primer nombre de la persona');
            $table->string('segundo_nombre', length: 100)->nullable()->comment('Segundo nombre de la persona');
            $table->string('tercer_nombre', length: 100)->nullable()->comment('Tercer nombre de la persona');
            $table->string('primer_apellido', length: 100)->comment('Primer apellido de la persona');
            $table->string('segundo_apellido', length: 100)->nullable()->comment('Segundo apellido de la persona');
            $table->string('tercer_apellido', length: 100)->nullable()->comment('Tercer apellido de la persona');
            $table->timestamp('fecha_nacimiento')->nullable()->comment('Fecha de nacimiento de la persona');
            $table->foreignId('sexo_id')->nullable()->comment('Sexo de la persona');
            $table->foreign('sexo_id')->references('id')->on('public.sexo')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->foreignId('sede_principal_id')->nullable()->comment('Sede principal donde está asignada la persona');
            $table->foreign('sede_principal_id')->references('id')->on('public.sede')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->boolean('permitir_editar')->default('false')->comment('Indica si la persona puede editar su perfil');

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
