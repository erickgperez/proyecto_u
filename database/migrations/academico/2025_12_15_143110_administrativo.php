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
        Schema::create('academico.administrativo', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');

            $table->comment('Tabla de administrativos');

            $table->string('codigo', 15)->unique();

            $table->foreignId('persona_id')->comment('Persona a la que está asignado el administrativo');
            $table->foreign('persona_id')->references('id')->on('public.persona')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.administrativo');
    }
};
