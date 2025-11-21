<?php

namespace App\Models\Documento;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Archivo extends Model
{
    use UserStamps, HasUuid, HasCreateMany;
    protected $table = 'documento.archivo';

    protected $fillable = [
        'nombre_original',
        'tipo',
        'ruta',
        'descripcion',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function documentos(): BelongsToMany
    {
        return $this->belongsToMany(Documento::class, 'documento.documento_archivo', 'archivo_id', 'documento_id');
    }
}
