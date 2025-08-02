<?php

namespace App\Models\Secundaria;

use Illuminate\Database\Eloquent\Model;

class Invitacion extends Model
{
    //
    protected $table = "secundaria.invitacion";

    protected $fillable = [
        'nie',
        'codigo'
    ];
}
