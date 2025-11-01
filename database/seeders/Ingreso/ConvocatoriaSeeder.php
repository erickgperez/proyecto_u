<?php

namespace Database\Seeders\Ingreso;

use App\Models\Ingreso\Convocatoria;
use App\Models\Workflow\Flujo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConvocatoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Convocatoria::insert([
            [
                'fecha' => Carbon::now(),
                'nombre' => '01-2026',
                'descripcion' => 'Primera convocatoria de ingreso 2026 - PRUEBA',
                'cuerpo_mensaje' => 'Te invitamos a participar en el proceso de ingreso a nuestras carreras',
                'flujo_id' => Flujo::where('codigo', 'INGRESO_01')->first()->id
            ],
        ]);
    }
}
