<?php

namespace Database\Seeders\Academico;

use App\Models\Academico\CarreraSede;
use App\Models\Academico\Estado;
use App\Models\Academico\Sede;
use App\Models\PlanEstudio\Carrera;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarreraSedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estadoId = Estado::where('codigo', 'ACTIVA')->first()->id;
        $sedeSS = Sede::where('codigo', 'S-SS')->first()->id;
        $sedeSO = Sede::where('codigo', 'S-SO')->first()->id;
        $sedeCH = Sede::where('codigo', 'S-CH')->first()->id;
        $sedeZA = Sede::where('codigo', 'S-ZA')->first()->id;
        $sedeMO = Sede::where('codigo', 'S-MO')->first()->id;
        $sedeLU = Sede::where('codigo', 'S-LU')->first()->id;

        CarreraSede::createMany([
            //Sede San Salvador
            //***carreras
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', '01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', '02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', '03')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', '04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', '05')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //*** certificaciones
            // Tecnologías de la información
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCABD01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCDFS01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCSRI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCREC01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCSIN01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCANU01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCGIV01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCIOT01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCARI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            // Mantenimiento industrial
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCIME02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCPFC02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCSIN02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCMAR02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCTMP02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //Tur
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCIPT04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCOST04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCOAG04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCEPP04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCOSE04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCIPC04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSS, 'carrera_id' => Carrera::where('codigo', 'MCGID04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],


            //Sede Sonsonate
            //***carreras
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', '01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', '04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', '05')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //*** certificaciones
            // Tecnologías de la información
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCABD01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCDFS01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCSRI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCREC01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCSIN01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCANU01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCGIV01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCIOT01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCARI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //Turismo
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCIPT04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCOST04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCOAG04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCEPP04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCOSE04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCIPC04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeSO, 'carrera_id' => Carrera::where('codigo', 'MCGID04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],


            // Sede Chalatenango
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', '01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', '03')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', '05')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //Certificaciones
            // Tecnologías de la Información 01
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCABD01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCDFS01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCSRI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCREC01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCSIN01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCANU01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCGIV01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCIOT01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeCH, 'carrera_id' => Carrera::where('codigo', 'MCARI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],



            // Sede Zacatecoluca
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', '01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', '05')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //Certificaciones
            // Tecnologías de la Información 01
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCABD01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCDFS01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCSRI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCREC01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCSIN01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCANU01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCGIV01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCIOT01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeZA, 'carrera_id' => Carrera::where('codigo', 'MCARI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],



            //Sede Morazán
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', '01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', '04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //Certificaciones
            // Tecnologías de la Información 01
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCABD01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCDFS01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCSRI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCREC01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCSIN01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCANU01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCGIV01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCIOT01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCARI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            // Turismo 04
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCIPT04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCOST04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCOAG04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCEPP04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCOSE04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCIPC04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeMO, 'carrera_id' => Carrera::where('codigo', 'MCGID04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],



            // Sede La Unión
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', '01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', '02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', '03')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', '04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', '05')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            //Certificaciones
            // Tecnologías de la Información 01
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCABD01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCDFS01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCSRI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCREC01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCSIN01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCANU01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCGIV01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCIOT01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCARI01')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            // Mantenimiento Industrial 02
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCIME02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCPFC02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCSIN02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCMAR02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCTMP02')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            // Turismo 04
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCIPT04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCOST04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCOAG04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCEPP04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCOSE04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCIPC04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],
            ['sede_id' => $sedeLU, 'carrera_id' => Carrera::where('codigo', 'MCGID04')->first()->id, 'cupo' => 10, 'estado_id' => $estadoId],

        ]);
    }
}
