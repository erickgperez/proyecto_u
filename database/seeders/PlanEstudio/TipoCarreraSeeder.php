<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\Grado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoCarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plan_estudio.tipo_carrera')->insert([
            ['codigo' => 'TECNICA', 'descripcion' => 'Carrera Técnica', 'grado_id' => Grado::where('codigo', '02')->first()->id],
            ['codigo' => 'CERTIFICACION', 'descripcion' => 'Certificación', 'grado_id' => null],
            ['codigo' => 'MICROCERTIFICACION', 'descripcion' => 'Microcertificación', 'grado_id' => null],
        ]);
    }
}
