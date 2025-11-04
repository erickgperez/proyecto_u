<?php

namespace App\Services;

use App\Models\Departamento;

class DistritoService
{
    public function distritosLikeTree()
    {
        //Obtener todos los departamentos
        $departamentos = Departamento::orderBy('descripcion', 'ASC')->get();

        $tree = [];
        foreach ($departamentos as $d) {
            $dChildren = [];
            //Obtener los municipios del departamentos
            $municipios = $d->municipios()->get();

            foreach ($municipios as $m) {
                $mChildren = [];
                //Obtener los distritos
                $distritos = $m->distritos()->get();
                foreach ($distritos as $dis) {
                    $mChildren[] = ['id' => $dis->id, 'descripcion' => $dis->descripcion, 'nombreCompleto' =>  $dis->nombreCompleto];
                }
                $dChildren[] = ['id' => 'muni' . $m->id, 'descripcion' => $m->descripcion, 'children' => $mChildren];
            }
            $tree[] = ['id' => 'depto' . $d->id, 'descripcion' => $d->descripcion, 'children' => $dChildren];
        }

        return $tree;
    }
}
