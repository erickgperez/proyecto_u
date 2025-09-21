<?php

namespace App\Models\Secundaria;

use App\Models\Estudio;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    //
    protected $table = "secundaria.carrera";

    public function estudios()
    {
        return $this->morphMany(Estudio::class, 'carrera');
    }
}
