<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoCarreraSedeSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workflow.tipo_carrera_sede_solicitud')->insert([
            ['codigo' => 'ORIGEN', 'descripcion' => 'La carrera será el origen'],
            ['codigo' => 'DESTINO', 'descripcion' => 'La carrera será el destino al aprobarse la solicitud'],
        ]);
    }
}
