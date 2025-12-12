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
        Schema::create('academico.semestre', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Los semestres o periodos académicos');
            $table->string('codigo', length: 100)->unique()->comment('Código identificador del semestre');
            $table->tinyInteger('anio')->required()->comment('Año del semestre');
            $table->string('descripcion', length: 255)->nullable()->comment('Texto descriptivo del semestre');
            $table->timestamp('fecha_inicio')->comment('Fecha de inicio del semestre');
            $table->timestamp('fecha_fin')->comment('Fecha de finalizacion del semestre');

            $table->foreignId('calendarizacion_id')->nullable()->comment('Calendario de eventos del semestre');
            $table->foreign('calendarizacion_id')->references('id')->on('public.calendarizacion')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('estado_id')->nullable()->comment('Estado del semestre');
            $table->foreign('estado_id')->references('id')->on('academico.estado')->onDelete('RESTRICT')->onUpdate('CASCADE');

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
        Schema::dropIfExists('academico.semestre');
    }
};
