<?php

namespace Database\Seeders\PlanEstudio;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoRequisitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudio.tipo_requisito')->insert([
            ['codigo' => 'PRERREQUISITO', 'descripcion' => 'Prerrequisito'],
            ['codigo' => 'CORREQUISITO', 'descripcion' => 'Correquisito'],
        ]);
    }
}
