<?php

namespace App\Services;

use App\Models\Ingreso\Aspirante;
use App\Models\Secundaria\Institucion;
use App\Models\Workflow\Solicitud;
use Illuminate\Database\Eloquent\Builder;

class SolicitudService
{
    public function getQB(): Builder
    {
        $qb = Solicitud::with([
            'solicitante',
            'etapa',
            'estado',
            'solicitudCarrerasSede',
            'sede',
            'modelo'
        ])
            ->select(
                'solicitud.*',
                'sector.descripcion as sector',
                'convocatoria_aspirante.seleccionado',
                'convocatoria_aspirante.solicitud_carrera_sede_id',
                'scs.carrera_sede_id',
                'carrera.nombre as nombre_carrera',
                'carrera.codigo as codigo_carrera',
                'tcss.codigo as opcion'
            )
            ->join('ingreso.aspirante as aspirante', function ($join) {
                $join->on('solicitud.solicitante_id', '=', 'aspirante.id')
                    ->where('solicitud.solicitante_type', '=', Aspirante::class);
            })
            ->leftJoin('ingreso.convocatoria_aspirante as convocatoria_aspirante', function ($join) {
                $join->on('aspirante.id', '=', 'convocatoria_aspirante.aspirante_id')
                    ->on('convocatoria_aspirante.convocatoria_id', '=', 'workflow.solicitud.modelo_id');
            })
            ->leftJoin('workflow.solicitud_carrera_sede as scs', 'scs.id', '=', 'convocatoria_aspirante.solicitud_carrera_sede_id')
            ->leftJoin('academico.carrera_sede as cs', 'cs.id', '=', 'scs.carrera_sede_id')
            ->leftJoin('plan_estudio.carrera as carrera', 'carrera.id', '=', 'cs.carrera_id')
            ->leftJoin('workflow.tipo_carrera_sede_solicitud as tcss', 'tcss.id', '=', 'scs.tipo_carrera_sede_solicitud_id')
            ->join('public.persona as persona', 'aspirante.persona_id', '=', 'persona.id')
            ->leftJoin('public.estudio as estudio', 'estudio.persona_id', '=', 'persona.id')
            ->leftJoin('secundaria.institucion as institucion', function ($join) {
                $join->on('estudio.institucion_id', '=', 'institucion.id')
                    ->where('estudio.institucion_type', '=', Institucion::class);
            })
            ->leftJoin('secundaria.sector as sector', 'institucion.sector_id', '=', 'sector.id');

        return $qb;
    }
}
