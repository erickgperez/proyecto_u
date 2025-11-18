<?php

namespace App\Models\Documento;

use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use UserStamps;

    protected $table = 'documento.tipo';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];
}
