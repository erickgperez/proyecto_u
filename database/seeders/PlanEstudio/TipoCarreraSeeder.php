<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\Grado;
use App\Models\PlanEstudio\TipoCarrera;
use Illuminate\Database\Seeder;

class TipoCarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoCarrera::createMany([
            ['codigo' => 'TECNICA', 'descripcion' => 'Carrera Técnica', 'grado_id' => Grado::where('codigo', '02')->first()->id],
            ['codigo' => 'CERTIFICACION', 'descripcion' => 'Certificación', 'grado_id' => null],
            ['codigo' => 'MICROCERTIFICACION', 'descripcion' => 'Microcertificación', 'grado_id' => null],
        ]);
    }
}
