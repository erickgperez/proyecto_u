<?php

namespace Database\Seeders\Workflow;

use App\Models\Workflow\TipoFlujo;
use Illuminate\Database\Seeder;

class TipoFlujoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoFlujo::createMany([
            ['codigo' => 'INGRESO', 'descripcion' => 'Proceso de ingreso universitario'],
            ['codigo' => 'CONFIGURACION_CONVOCATORIA', 'descripcion' => 'Proceso de configuración de convocatoria'],
        ]);
    }
}
