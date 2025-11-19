<?php

namespace Database\Seeders\PlanEstudio;

use App\Models\PlanEstudio\Carrera;
use App\Models\PlanEstudio\CarreraUnidadAcademica;
use App\Models\PlanEstudio\Requisitos;
use App\Models\PlanEstudio\TipoRequisito;
use App\Models\PlanEstudio\UnidadAcademica;
use Illuminate\Database\Seeder;

class RequisitosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipo = TipoRequisito::where('codigo', 'PRERREQUISITO')->first()->id;
        $queryBase = CarreraUnidadAcademica::from('plan_estudio.carrera_unidad_academica as A')
            ->select('A.id')
            ->join('plan_estudio.unidad_academica as B', 'A.unidad_academica_id', '=', 'B.id');
        $requisitosConsolidados = [];

        //Asignaturas de Tecnologías de la Información
        $carrera = Carrera::where('codigo', '01')->first()->id;
        $query = (clone $queryBase)->where('carrera_id', $carrera);

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'BDA0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['LDI0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PRO0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['LDI0101', 'IRE0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'FSE0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['LDI0101', 'PRE0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'ANU0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['IRE0101', 'SOP0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'RCR0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['FTI0101', 'LDI0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'BDN0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['BDA0101', 'RCR0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PMO0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PRO0101', 'ANU0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'ISR0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['FSE0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'VIR0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['FSE0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'ICO0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['BDA0101', 'FSE0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'ABD0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['BDN0101', 'VIR0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'DFS0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['BDN0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'IRI0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['ISR0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'DRN0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PMO0101', 'ICO0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PIN0101')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PMO0101', 'ICO0101'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }


        //Asignaturas de Mantenimiento industrial
        $carrera = Carrera::where('codigo', '02')->first()->id;
        $query = (clone $queryBase)->where('carrera_id', $carrera);

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'FMC0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MTA0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'FFT0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MTA0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'SHI0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['IMI0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'EAM0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MEM0201', 'SRM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PMH0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MIN0201', 'MEM0201', 'SRM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'TLU0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['FFT0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'MBC0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SHI0201', 'EAM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'MAR0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SHI0201', 'EAM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'TEM0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['FMC0201', 'SHI0201', 'EAM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'TSO0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['EAM0201', 'PMH0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'MMT0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['TLU0201', 'TEM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'CPM0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MBC0201', 'MAR0201', 'TEM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'MMA0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['TEM0201', 'TSO0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'CAR0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['TEM0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'TMP0201')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['TLU0201', 'TEM0201', 'TSO0201'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }


        //Asignaturas de Industrias Alimentarias
        $carrera = Carrera::where('codigo', '03')->first()->id;
        $query = (clone $queryBase)->where('carrera_id', $carrera);

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'FAP0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['BGE0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'QOR0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['BGE0301', 'SHO0301', 'EGA0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'MGA0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MAP0301', 'SHO0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PCE0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['QGE0301', 'SHO0301', 'EGA0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PFH0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['QGE0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'CCO0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MGA0301', 'PCE0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'QAN0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['QOR0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'BGA0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['QOR0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'EAP0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['MGA0301', 'PCE0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PCA0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['FAP0301', 'PFH0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PCP0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['CCO0301', 'EAP0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'AIN0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PCA0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'AIN0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PCA0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'CIA0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['EAP0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'DEX0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['QAN0301', 'BGA0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PLE0301')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['CCO0301', 'EAP0301', 'PCA0301'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }


        //Asignaturas de Turismo
        $carrera = Carrera::where('codigo', '04')->first()->id;
        $query = (clone $queryBase)->where('carrera_id', $carrera);

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PNC0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['HSE0401', 'GTU0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'IAL0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['NNI0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'DTE0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['GTU0401', 'TDS0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'ADT0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PES0401', 'DTE0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'OGA0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['IAL0401', 'DTE0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'GSC0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['TIC0401', 'DTE0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'FEC0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PES0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'OAL0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PNC0401', 'IAL0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'AOT0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['ADT0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'MTU0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['GSC0401', 'FEC0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'IBP0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['OGA0401', 'OAL0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'RPT0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PNC0401', 'OGA0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'OTU0401')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PNC0401', 'OGA0401', 'OAL0401'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        //Asignaturas de Eficiencia Energética
        $carrera = Carrera::where('codigo', '05')->first()->id;
        $query = (clone $queryBase)->where('carrera_id', $carrera);

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PMA0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SOC0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'SMT0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SOC0501', 'IER0501', 'PEL0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'IPD0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PRE0501', 'IER0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'ERE0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SEN0501', 'IER0501', 'PEL0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'IPE0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SEN0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'SSF0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['IPD0501', 'ERE0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'SCP0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SMT0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'MEL0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SMT0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'GDV0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['IPD0501', 'ERE0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PEE0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PMA0501', 'IPE0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'CLI0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['PEE0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'SAC0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['GDV0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'CAR0501')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SCP0501', 'MEL0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }

        $carreraUnidadAcademica = (clone $query)->where('B.codigo', 'PIN0502')->first()->id;
        $requisitos = (clone $query)->whereIn('B.codigo', ['SSF0501', 'GDV0501', 'PEE0501'])->get();
        foreach ($requisitos as $r) {
            $requisitosConsolidados[] = ['unidad' => $carreraUnidadAcademica, 'requisito' => $r->id];
        }



        foreach ($requisitosConsolidados as $r) {
            Requisitos::insert([
                [
                    'carrera_unidad_academica_id' => $r['unidad'],
                    'carrera_unidad_academica_requisito_id' => $r['requisito'],
                    'tipo_requisito_id' => $tipo
                ],
            ]);
        }
    }
}
