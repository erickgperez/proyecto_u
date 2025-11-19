<?php

namespace App\Services;

use App\Models\Ingreso\Convocatoria;
use Illuminate\Support\Facades\DB;

class CarreraSedeService
{
    public function getSedesCarreras(?Convocatoria $convocatoria = null)
    {
        $query = DB::table('academico.carrera_sede as cs')
            ->select(
                's.id as sede_id',
                's.nombre as sede_nombre',
                'tc.id as tipo_carrera_id',
                'tc.descripcion as tipo_carrera',
                'cs.id as id',
                'c.nombre as nombre_carrera',
                'c.codigo as codigo_carrera'
            )
            ->join('plan_estudio.carrera as c', 'cs.carrera_id', '=', 'c.id')
            ->join('academico.sede as s', 'cs.sede_id', '=', 's.id')
            ->join('plan_estudio.tipo_carrera as tc', 'c.tipo_carrera_id', '=', 'tc.id')
            ->orderBy('s.nombre')
            ->orderBy('tc.descripcion')
            ->orderBy('c.nombre');

        if ($convocatoria != null) {
            $query->join('ingreso.convocatoria_carrera_sede as ccs', 'cs.id', '=', 'ccs.carrera_sede_id')
                ->where('ccs.convocatoria_id', $convocatoria->id);
        }
        $carrerasSedes = $query->get();

        $sedesCarreras = [];

        foreach ($carrerasSedes as $cs) {
            $sedesCarreras[$cs->sede_id]['id'] = 's-' . $cs->sede_id;
            $sedesCarreras[$cs->sede_id]['title'] = $cs->sede_nombre;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['id'] = $cs->sede_id . '-' . $cs->tipo_carrera_id;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['title'] = $cs->tipo_carrera;
            $sedesCarreras[$cs->sede_id]['childrenn'][$cs->tipo_carrera_id]['children'][] = ['id' => $cs->id, 'title' => '(' . $cs->codigo_carrera . ') ' . $cs->nombre_carrera];
        }

        $items = [];
        foreach ($sedesCarreras as $sc) {
            $sc_ = $sc;
            foreach ($sc['childrenn'] as $c) {
                $sc_['children'][] = $c;
            }
            unset($sc_['childrenn']);
            $items[] = $sc_;
        }

        return $items;
    }
}
