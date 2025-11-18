<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\CarreraUnidadAcademica;
use App\Models\PlanEstudio\TipoUnidadAcademica;
use App\Models\PlanEstudio\UnidadAcademica;
use Illuminate\Database\Seeder;

class CarreraUnidadAcademicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carrera = Carrera::where('codigo', '01')->first()->id;
        //Asignaturas de Tecnologías de la Información
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FTI0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'LDI0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IRE0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SOP0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PRE0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'BDA0101')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PRO0101')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FSE0101')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ANU0101')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'RCR0101')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'BDN0101')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PMO0101')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ISR0101')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'VIR0101')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ICO0101')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ABD0101')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'DFS0101')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IRI0101')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'DRN0101')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PIN0101')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);
        //Certificaciones de TI
        $carrera = Carrera::where('codigo', 'MCABD01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'BDA0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'BDN0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ABD0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCDFS01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PRO0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PMO0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'DFS0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCSRI01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IRE0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SOP0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCREC01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'RCR0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IRI0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCSIN01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FSE0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ISR0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCANU01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ANU0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'DRN0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCGIV01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ANU0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'VIR0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCIOT01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'RCR0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ICO0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCARI01')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IRE0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IRI0101')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);


        $carrera = Carrera::where('codigo', '02')->first()->id;
        //Asignaturas de Mantenimiento Industrial
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MTA0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MIN0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IMI0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MEM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SRM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FMC0201')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FFT0201')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SHI0201')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'EAM0201')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PMH0201')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TLU0201')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MBC0201')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MAR0201')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TEM0201')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TSO0201')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MMT0201')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'CPM0201')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MMA0201')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'CAR0201')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TMP0201')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCIME02')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MEM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SHI0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'EAM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCPFC02')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MIN0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MEM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SRM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SHI0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PMH0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCSIN02')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MIN0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MEM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SRM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SHI0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TSO0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCMAR02')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FFT0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SHI0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'EAM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MAR0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCTMP02')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IMI0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TEM0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TMP0201')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);


        $carrera = Carrera::where('codigo', '03')->first()->id;
        //Asignaturas de Industrias Alimentarias
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MAP0301')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'QGE0301')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'BGE0301')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SHO0301')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'EGA0301')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FAP0301')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'QOR0301')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MGA0301')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PCE0301')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PFH0301')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'CCO0301')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'QAN0301')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'BGA0301')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'EAP0301')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PCA0301')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PCP0301')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'AIN0301')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'CIA0301')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'DEX0301')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PLE0301')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);



        $carrera = Carrera::where('codigo', '04')->first()->id;
        //Asignaturas de Turismo
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TIC0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'HSE0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'GTU0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TDS0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'NNI0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FMF0401')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PNC0401')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PES0401')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IAL0401')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'DTE0401')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ADT0401')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'OGA0401')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'GSC0401')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'FEC0401')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'OAL0401')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'AOT0401')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MTU0401')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IBP0401')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'RPT0401')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'OTU0401')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],

        ]);

        $carrera = Carrera::where('codigo', 'MCIPT04')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TIC0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'HSE0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'GTU0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'TDS0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCOST04')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PNC0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IAL0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'GSC0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCOAG04')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'OGA0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'OAL0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCEPP04')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'AOT0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'RPT0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCOSE04')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IBP0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCIPC04')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'AOT0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MTU0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'RPT0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);

        $carrera = Carrera::where('codigo', 'MCGID04')->first()->id;
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'AOT0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IBP0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'RPT0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'OTU0401')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);


        $carrera = Carrera::where('codigo', '05')->first()->id;
        //Asignaturas de Eficiencia Energética
        CarreraUnidadAcademica::insert([
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PRE0501')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SEN0501')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SOC0501')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IER0501')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PEL0501')->first()->id, 'semestre' => 1, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PMA0501')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SMT0501')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IPD0501')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'ERE0501')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'IPE0501')->first()->id, 'semestre' => 2, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SSF0501')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SCP0501')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'MEL0501')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'GDV0501')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PEE0501')->first()->id, 'semestre' => 3, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SHT0501')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'CLI0501')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'SAC0501')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'CAR0501')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
            ['carrera_id' => $carrera, 'unidad_academica_id' => UnidadAcademica::where('codigo', 'PIN0502')->first()->id, 'semestre' => 4, 'obligatoria' => true, 'requisito_creditos' => 0],
        ]);
    }
}
