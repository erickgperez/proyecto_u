<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoFlujoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workflow.tipo_flujo')->insert([
            ['codigo' => 'INGRESO', 'descripcion' => 'Proceso de ingreso universitario'],
        ]);
    }
}
