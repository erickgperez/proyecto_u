<?php

namespace Database\Seeders\Academico;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academico.sede')->insert([
            ['codigo' => 'ESTUDIANTE_CARRERA_SEDE', 'descripcion' => 'Estados a utilizar en la tabla academico.estudiante_carrera_sede'],
            ['codigo' => 'CARRERA_SEDE', 'descripcion' => 'Estados a utilizar en la tabla academico.carrera_sede'],
            ['codigo' => 'CARRERA', 'descripcion' => 'Estados a utilizar en plan_estudio.carrera'],
        ]);
    }
}
