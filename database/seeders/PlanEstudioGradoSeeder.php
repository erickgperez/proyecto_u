<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanEstudioGradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudio.grado')->insert([
            ['codigo' => '01', 'descripcion_masculino' => 'Técnico', 'descripcion_femenino' => 'Técnica'],
        ]);
    }
}
