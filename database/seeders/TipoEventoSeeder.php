<?php

namespace Database\Seeders;

use App\Models\TipoCalendarizacion;
use App\Models\TipoEvento;
use Illuminate\Database\Seeder;

class TipoEventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoEvento::insert([
            ['codigo' => 'ACTIVIDAD', 'descripcion' => 'Actividad general', 'tipo_calendarizacion_id' => null],
            ['codigo' => 'INSCRIPCION', 'descripcion' => 'Periodo de inscripción de asignaturas', 'tipo_calendarizacion_id' => TipoCalendarizacion::where('codigo', 'SEMESTRE')->first()->id],
            ['codigo' => 'RETIRO_ASIGNATURAS', 'descripcion' => 'Periodo de retiro de asignaturas', 'tipo_calendarizacion_id' => TipoCalendarizacion::where('codigo', 'SEMESTRE')->first()->id],
        ]);
    }
}
