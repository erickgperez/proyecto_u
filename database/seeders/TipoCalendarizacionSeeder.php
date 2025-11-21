<?php

namespace Database\Seeders;

use App\Models\TipoCalendarizacion;
use Illuminate\Database\Seeder;

class TipoCalendarizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoCalendarizacion::createMany([
            ['codigo' => 'CONVOCATORIA', 'descripcion' => 'Para identificar calendarios de convocatorias'],
            ['codigo' => 'SEMESTRE', 'descripcion' => 'Identifica calendarios de semestre académico'],
        ]);
    }
}
