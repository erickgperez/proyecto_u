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
        Schema::create('plan_estudio.tipo_carrera', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');

            $table->comment('Tipo de la carrera, por ejemplo: Técnica, Ingeniería, Licenciatura...');
            $table->string('codigo', length: 20)->unique();
            $table->string('descripcion', length: 50)->comment('Texto descriptivo del tipo de carrera');
            $table->foreignId('grado_id')->nullable()->comment('Relación para determinar el grado que otorga la carrera');
            $table->foreign('grado_id')->references('id')->on('plan_estudio.grado')->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_estudio.tipo_carrera');
    }
};
