<?php

namespace Database\Seeders;

use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\TipoCarrera;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanEstudioCarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carrera::insert([
            ['codigo' => 'MI', 'nombre' => 'Mantenimiento Industrial', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'EE', 'nombre' => 'Eficiencia Energética', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'TI', 'nombre' => 'Tecnologías de la Información', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'TUR', 'nombre' => 'Turismo', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'ALI', 'nombre' => 'Industrias Alimentarias', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null],
            ['codigo' => 'BTC', 'nombre' => 'Bitcoin y Tecnologías Emergentes', 'tipo_carrera_id' => TipoCarrera::where('codigo', 'TECNICA')->first()->id, 'certificacion_de' => null],
        ]);
    }
}
