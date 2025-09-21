<?php

namespace App\Models\Secundaria;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    //
    protected $table = "secundaria.institucion";

    public function estudios()
    {
        return $this->morphMany(Institucion::class, 'institucion');
    }
}
