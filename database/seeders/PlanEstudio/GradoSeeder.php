<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\Grado;
use Illuminate\Database\Seeder;

class GradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grado::createMany([
            ['codigo' => '01', 'descripcion_masculino' => 'Bachiller', 'descripcion_femenino' => 'Bachiller'],
            ['codigo' => '02', 'descripcion_masculino' => 'Técnico', 'descripcion_femenino' => 'Técnica'],
        ]);
    }
}
