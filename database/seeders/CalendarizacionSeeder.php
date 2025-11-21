<?php

namespace Database\Seeders;

use App\Models\Calendarizacion;
use App\Models\TipoCalendarizacion;
use Illuminate\Database\Seeder;

class CalendarizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Agregarle un calendario a la convocatoria
        Calendarizacion::create([
            'codigo' => '01-2026',
            'descripcion' => 'Calendario asociado a convocatoria de prueba',
            'tipo_calendarizacion_id' => TipoCalendarizacion::where('codigo', 'CONVOCATORIA')->first()->id,
        ]);
    }
}
