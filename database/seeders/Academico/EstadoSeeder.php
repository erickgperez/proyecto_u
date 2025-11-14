<?php

namespace Database\Seeders\Academico;

use App\Models\Academico\UsoEstado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academico.estado')->insert([
            ['codigo' => 'ESTUDIANTE', 'descripcion' => 'Estudiante activo', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],
            ['codigo' => 'EGRESADO', 'descripcion' => 'Egresado de la carrera', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],
            ['codigo' => 'GRADUADO', 'descripcion' => 'Estudiante graduado', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],
            ['codigo' => 'RETIRADO', 'descripcion' => 'Estudiante no está activo en su carrera', 'uso_estado_id' => UsoEstado::where('codigo', 'ESTUDIANTE_CARRERA_SEDE')->first()->id],

            ['codigo' => 'ACTIVA', 'descripcion' => 'La carrera está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA_SEDE')->first()->id],
            ['codigo' => 'INACTIVA', 'descripcion' => 'La carrera ya no está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA_SEDE')->first()->id],

            ['codigo' => 'VIGENTE', 'descripcion' => 'La carrera está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA')->first()->id],
            ['codigo' => 'EN_EDICION', 'descripcion' => 'Se están ingresando los datos de la carrera', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA')->first()->id],
            ['codigo' => 'ANTIGUA', 'descripcion' => 'La carrera ya no está vigente', 'uso_estado_id' => UsoEstado::where('codigo', 'CARRERA')->first()->id],

        ]);
    }
}
