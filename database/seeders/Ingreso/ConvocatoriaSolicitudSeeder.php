<?php

namespace Database\Seeders\Ingreso;

use App\Models\Ingreso\Convocatoria;
use App\Models\User;
use App\Models\Workflow\Estado;
use App\Models\Workflow\Etapa;
use App\Models\Workflow\Flujo;
use App\Models\Workflow\Historial;
use App\Models\Workflow\Solicitud;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConvocatoriaSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $solicitud = Solicitud::create(
            [
                'solicitante_id' => Convocatoria::where('nombre', '01-2026')->first()->id,
                'solicitante_type' => 'App\Models\Ingreso\Convocatoria',
                'flujo_id' => Flujo::where('codigo', 'CONFIGURACION_CONVOCATORIA')->first()->id,
                'etapa_id' => Etapa::where('codigo', 'OFERTA')->first()->id,
                'estado_id' => Estado::where('codigo', 'INICIO')->first()->id,
                'created_at' => Carbon::now(),
                'created_by' => 1,
            ],
        );

        Historial::create([
            'solicitud_id' => $solicitud->id,
            'etapa_id' => Etapa::where('codigo', 'OFERTA')->first()->id,
            'estado_id' => Estado::where('codigo', 'INICIO')->first()->id,
            'created_at' => Carbon::now(),
            'created_by' => 1,
        ]);
    }
}
