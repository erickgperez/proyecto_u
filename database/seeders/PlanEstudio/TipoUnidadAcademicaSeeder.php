<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\Grado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoUnidadAcademicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudio.tipo_unidad_academica')->insert([
            ['codigo' => 'ASIGNATURA', 'descripcion' => 'Asignatura'],
            ['codigo' => 'CURSO', 'descripcion' => 'Curso'],
            ['codigo' => 'MODULO', 'descripcion' => 'Módulo'],
            ['codigo' => 'PROYECTO_INTEGRADOR', 'descripcion' => 'Proyecto integrador'],
        ]);
    }
}
