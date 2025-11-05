<?php

namespace Database\Seeders\Workflow;

use App\Models\Workflow\TipoFlujo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FlujoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workflow.flujo')->insert([
            ['codigo' => 'INGRESO_01', 'nombre' => 'Proceso de ingreso universitario', 'activo' => true, 'tipo_flujo_id' => TipoFlujo::where('codigo', 'INGRESO')->first()->id],
            ['codigo' => 'CONFIGURACION_CONVOCATORIA', 'nombre' => 'Proceso de configuración de las convocatorias de ingreso', 'activo' => true, 'tipo_flujo_id' => TipoFlujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id],
        ]);
    }
}
