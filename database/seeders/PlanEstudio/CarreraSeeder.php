<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\Academico\Estado;
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
        Carrera::createMany([
            ['codigo' => '01', 'nombre' => 'Tecnologías de la Información', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => '02', 'nombre' => 'Mantenimiento Industrial', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => '03', 'nombre' => 'Industrias Alimentarias', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => '04', 'nombre' => 'Turismo', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            ['codigo' => '05', 'nombre' => 'Eficiencia Energética', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
            //['codigo' => '06', 'nombre' => 'Bitcoin y Tecnologías Emergentes', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null, 'estado_id' => $estadoId],
        ]);
    }
}
