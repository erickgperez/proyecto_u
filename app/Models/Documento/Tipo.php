<?php

namespace App\Models\Documento;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    protected $table = 'documento.tipo';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];
}
