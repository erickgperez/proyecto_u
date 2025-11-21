<?php

namespace Database\Seeders\Workflow;

use App\Models\Workflow\Estado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Estado::createMany([
            ['codigo' => 'INICIO', 'descripcion' => 'Solicitud iniciada'],
            ['codigo' => 'EN_TRAMITE', 'descripcion' => 'Solicitud está en trámite'],
            ['codigo' => 'REVISION', 'descripcion' => 'La solicitud está siendo revisada'],
            ['codigo' => 'OBSERVADA', 'descripcion' => 'Se ha realizado una observación en algún paso de la solicitud'],
            ['codigo' => 'APROBADA', 'descripcion' => 'Solicitud ha sido aprobada'],
            ['codigo' => 'DENEGADA', 'descripcion' => 'Se ha denegado la solicitud'],
        ]);
    }
}
