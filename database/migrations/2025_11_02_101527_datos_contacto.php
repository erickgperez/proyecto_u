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
        Schema::create('datos_contacto', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Datos de contacto asociados a persona)');
            //$table->string('email_principal', length: 100)->nullable()->unique();
            $table->string('email_alternativo', length: 100)->nullable();
            $table->string('direccion_residencia', length: 500)->nullable();
            $table->foreignId('residencia_distrito_id')->nullable()->comment('Distrito de residencia');
            $table->foreign('residencia_distrito_id')->references('id')->on('distrito')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->string('telefono_residencia', length: 50)->nullable();
            $table->string('direccion_trabajo', length: 500)->nullable();
            $table->string('telefono_trabajo', length: 50)->nullable();
            $table->string('telefono_personal', length: 50)->nullable();
            $table->string('telefono_personal_alternativo', length: 50)->nullable();
            $table->boolean('permitir_editar')->default('true');

            $table->foreignId('persona_id');
            $table->foreign('persona_id')->references('id')->on('public.persona')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('datos_contacto');
    }
};
