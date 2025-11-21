<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\TipoRequisito;
use Illuminate\Database\Seeder;

class TipoRequisitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoRequisito::createMany([
            ['codigo' => 'PRERREQUISITO', 'descripcion' => 'Prerrequisito'],
            ['codigo' => 'CORREQUISITO', 'descripcion' => 'Correquisito'],
        ]);
    }
}
