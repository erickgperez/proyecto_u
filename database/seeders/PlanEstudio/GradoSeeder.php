<?php

namespace Database\Seeders\PlanEstudio;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudio.grado')->insert([
            ['codigo' => '01', 'descripcion_masculino' => 'Bachiller', 'descripcion_femenino' => 'Bachiller'],
            ['codigo' => '02', 'descripcion_masculino' => 'Técnico', 'descripcion_femenino' => 'Técnica'],
        ]);
    }
}
