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
        Schema::create('secundaria.data_bachillerato', function (Blueprint $table) {
            //$table->id();
            $table->uuid('uuid')->unique()->after('id');
            $table->comment('Tabla para subir temporalmente los datos leídos desde el archivo de excel');
            $table->string('nie', length: 15)->unique()->comment('Número de identificación de estudiante');
            $table->string('correo', length: 150)->nullable()->comment('Correo electrónico que se usará para contactar al estudiante');
            $table->string('primer_nombre', length: 100);
            $table->string('segundo_nombre', length: 100)->nullable();
            $table->string('tercer_nombre', length: 100)->nullable();
            $table->string('primer_apellido', length: 100);
            $table->string('segundo_apellido', length: 100)->nullable();
            $table->string('tercer_apellido', length: 100)->nullable();
            $table->string('sexo', length: 10)->nullable();
            $table->unsignedTinyInteger('edad')->nullable();
            $table->string('sector', length: 10)->nullable()->comment('Sector de la institución en que estudia el alumno');
            $table->string('grado', length: 15)->nullValue()->comment('Grado que cursa');
            $table->string('codigo_ce', length: 15)->nullable()->comment('Código del centro educativo');
            $table->string('nombre_centro_educativo', length: 255)->nullable();
            $table->string('direccion', length: 255)->nullable()->comment('Dirección del centro educativo');
            $table->string('codigo_depto', length: 5)->nullable();
            $table->string('departamento', length: 100)->nullable();
            $table->string('codigo_distrito', length: 7)->nullable();
            $table->string('distrito', length: 100)->nullable();
            $table->string('opcion_bachillerato', length: 100)->nullable()->comment('Opción de bachillerato que estudia el alumno');
            $table->float('calificacion_bachillerato', 5, 2)->nullable()->comment('Calificación obtenida en la prueba que se realiza en educación media');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_bachillerato');
    }
};
