<?php

namespace App\Models\Documento;

use App\Models\Persona;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Documento extends Model
{
    use UserStamps;
    protected $table = 'documento.documento';

    protected $fillable = [
        'numero',
        'fecha_emision',
        'fecha_expiracion',
        'revisado',
        'tipo_id'
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(Tipo::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function archivos(): BelongsToMany
    {
        return $this->belongsToMany(Archivo::class, 'documento.documento_archivo', 'archivo_id', 'documento_id');
    }

    public function personas(): BelongsToMany
    {
        return $this->belongsToMany(Persona::class, 'documento.persona_documento', 'persona_id', 'documento_id');
    }
}
