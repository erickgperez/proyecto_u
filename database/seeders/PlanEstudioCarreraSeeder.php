<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanEstudioTipoCarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudio.carrera')->insert([
            ['codigo' => 'TECNICA', 'nombre' => 'Técnica', 'tipo_carrera_id' => DB::table('plan_estudio.tipo_carrera')->where('codigo', 'TECNICA')->first()->id],
        ]);
    }
}
