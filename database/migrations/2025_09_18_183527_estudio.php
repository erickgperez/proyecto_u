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
        Schema::create('estudio', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Los estudios realizados por una persona');
            $table->string('nombre_titulo', length: 255)->nullable()->comment('Nombre del título obtenido');
            $table->string('nombre_institucion', length: 255)->nullable()->comment('Nombre de la institución donde se realizó');
            $table->timestamp('fecha_finalizacion')->nullable();

            $table->foreignId('persona_id')->nullable()->comment('Relación para identificar la persona a la que pertenece el estudio');
            $table->foreign('persona_id')->references('id')->on('persona')->onDelete('CASCADE')->onUpdate('CASCADE');

            $table->foreignId('grado_id')->nullable()->comment('Relación para determinar el grado obtenido');
            $table->foreign('grado_id')->references('id')->on('plan_estudio.grado')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('documento_id')->nullable()->comment('El archivo del título obtenido');
            $table->foreign('documento_id')->references('id')->on('documento.documento')->onDelete('RESTRICT')->onUpdate('CASCADE');

            $table->foreignId('pais_id')->nullable()->comment('País donde se realizó el estudio. Se usará cuando la institución donde realizó el estudio no esté registrada en la base de datos y solo se haya ingresado el nombre');
            $table->foreign('pais_id')->references('id')->on('pais')->onDelete('RESTRICT')->onUpdate('CASCADE');

            // relación muchos a uno polimórfica (el registro padre puede ser de secundaria.carrera o plan_estudio.carrera)
            $table->unsignedBigInteger('carrera_id')->nullable()->comment('id de la carrera. Es una relación muchos a uno polimórfica (el registro padre puede ser de secundaria.carrera o plan_estudio.carrera)');
            $table->string('carrera_type', length: 255)->nullable()->comment('Nombre de la tabla con la que se relaciona (secundaria.carrera o plan_estudio.carrera)');
            //$table->morphs('carrera')->comment();

            // relación muchos a uno polimórfica (el registro padre puede ser de secundaria.institucion o plan_estudio.institucion)
            $table->unsignedBigInteger('institucion_id')->nullable()->comment('id de la institución donde realizó el estudio. Es una relación muchos a uno polimórfica (el registro padre puede ser de secundaria.carrera o plan_estudio.carrera)');
            $table->string('institucion_type', length: 255)->nullable()->comment('Nombre de la tabla con la que se relaciona (secundaria.carrera o plan_estudio.carrera)');
            //$table->morphs('institucion');

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
