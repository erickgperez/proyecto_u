<?php

namespace App\Models\Secundaria;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    use HasUuid, HasCreateMany;

    protected $table = "secundaria.institucion";

    public function estudios()
    {
        return $this->morphMany(Institucion::class, 'institucion');
    }
}
