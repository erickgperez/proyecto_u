<?php

namespace Database\Seeders\Academico;

use App\Models\Academico\UsoEstado;
use Illuminate\Database\Seeder;

class UsoEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UsoEstado::createMany([
            ['codigo' => 'ESTUDIANTE_CARRERA_SEDE', 'descripcion' => 'Estados a utilizar en la tabla academico.estudiante_carrera_sede'],
            ['codigo' => 'CARRERA_SEDE', 'descripcion' => 'Estados a utilizar en la tabla academico.carrera_sede'],
            ['codigo' => 'CARRERA', 'descripcion' => 'Estados a utilizar en plan_estudio.carrera'],
            ['codigo' => 'EXPEDIENTE', 'descripcion' => 'Estados a utilizar en el expediente del estudiante'],
        ]);
    }
}
