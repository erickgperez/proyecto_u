<?php

namespace Database\Seeders\Academico;

use App\Models\Academico\Estado;
use App\Models\Academico\UsoEstado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Estado::createMany([
            ['codigo' => 'ESTUDIANTE', 'descripcion' => 'Estudiante activo', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],
            ['codigo' => 'EGRESADO', 'descripcion' => 'Egresado de la carrera', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],
            ['codigo' => 'GRADUADO', 'descripcion' => 'Estudiante graduado', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],
            ['codigo' => 'RETIRADO', 'descripcion' => 'Estudiante no está activo en su carrera', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],

            ['codigo' => 'ACTIVA', 'descripcion' => 'La carrera está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA_SEDE')->first()->id],
            ['codigo' => 'INACTIVA', 'descripcion' => 'La carrera ya no está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA_SEDE')->first()->id],

            ['codigo' => 'VIGENTE', 'descripcion' => 'La carrera está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA')->first()->id],
            ['codigo' => 'EN_EDICION', 'descripcion' => 'Se están ingresando los datos de la carrera', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA')->first()->id],
            ['codigo' => 'ANTIGUA', 'descripcion' => 'La carrera ya no está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA')->first()->id],

            ['codigo' => 'AP', 'descripcion' => 'Aprobada', 'uso_estado_id' => UsoEstado::where('codigo', 'EXPEDIENTE')->first()->id],
            ['codigo' => 'RP', 'descripcion' => 'Reprobada', 'uso_estado_id' => UsoEstado::where('codigo', 'EXPEDIENTE')->first()->id],
            ['codigo' => 'RT', 'descripcion' => 'Retirada', 'uso_estado_id' => UsoEstado::where('codigo', 'EXPEDIENTE')->first()->id],
            ['codigo' => 'EC', 'descripcion' => 'En curso', 'uso_estado_id' => UsoEstado::where('codigo', 'EXPEDIENTE')->first()->id],

        ]);
    }
}
