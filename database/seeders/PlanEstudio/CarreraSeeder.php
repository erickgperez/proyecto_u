<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\Academica\Estado;
use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\TipoCarrera;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estadoId = Estado::where('codigo', 'VIGENTE')->first()->id;
        Carrera::insert([
            ['codigo' => 'MI', 'nombre' => 'Mantenimiento Industrial', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'EE', 'nombre' => 'Eficiencia Energética', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'TI', 'nombre' => 'Tecnologías de la Información', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'TUR', 'nombre' => 'Turismo', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'ALI', 'nombre' => 'Industrias Alimentarias', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => 'BTC', 'nombre' => 'Bitcoin y Tecnologías Emergentes', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
        ]);
    }
}
