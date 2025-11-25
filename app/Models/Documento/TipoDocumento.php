<?php

namespace App\Models\Documento;

use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;

class TipoDocumento extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'documento.tipo';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'documento.tipo_rol', 'tipo_id', 'rol_id');
    }
}
