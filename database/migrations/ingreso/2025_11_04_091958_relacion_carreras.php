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
        Schema::create('ingreso.relacion_carreras', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');

            $table->comment('Relación para definir relaciones entre carreras universitarias y carreras de bachillerato');

            $table->foreignId('carrera_universitaria_id')->comment('Id de la carrera universitaria');
            $table->foreign('carrera_universitaria_id')->references('id')->on('plan_estudio.carrera')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('carrera_secundaria_id')->comment('Id de la carrera de bachillerato');
            $table->foreign('carrera_secundaria_id')->references('id')->on('secundaria.carrera')->onDelete('CASCADE')->onUpdate('CASCADE');

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
        Schema::dropIfExists('ingreso.relacion_carreras');
    }
};
