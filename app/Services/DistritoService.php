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
                    $mChildren[] = ['id' => $dis->id, 'title' => $dis->descripcion, 'name' =>  $dis->descripcion . ' / ' . $m->descripcion . ' / ' . $d->descripcion];
                }
                $dChildren[] = ['id' => 'muni' . $m->id, 'title' => $m->descripcion, 'children' => $mChildren];
            }
            $tree[] = ['id' => 'depto' . $d->id, 'title' => $d->descripcion, 'children' => $dChildren];
        }

        return $tree;
    }
}
