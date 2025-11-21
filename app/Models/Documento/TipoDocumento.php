<?php

namespace App\Models\Documento;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'documento.tipo';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];
}
