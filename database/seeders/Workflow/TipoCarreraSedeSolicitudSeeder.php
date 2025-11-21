<?php

namespace Database\Seeders\Workflow;

use App\Models\Workflow\TipoCarreraSedeSolicitud;
use Illuminate\Database\Seeder;

class TipoCarreraSedeSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoCarreraSedeSolicitud::createMany([
            ['codigo' => 'ORIGEN', 'descripcion' => 'La carrera será el origen'],
            ['codigo' => 'DESTINO', 'descripcion' => 'La carrera será el destino al aprobarse la solicitud'],
            ['codigo' => 'PRIMERA_OPCION', 'descripcion' => 'La carrera principal en el proceso de ingreso'],
            ['codigo' => 'SEGUNDA_OPCION', 'descripcion' => 'La carrera alternativa 1'],
            ['codigo' => 'TERCERA_OPCION', 'descripcion' => 'La carrera alternativa 2'],
        ]);
    }
}
