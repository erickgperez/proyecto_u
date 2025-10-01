<?php

namespace App\Models;

use App\Models\Documento\Documento;
use App\Models\Ingreso\Aspirante;
use App\Models\Sexo;
use App\Models\User;
use App\Models\Workflow\Solicitud;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    use UserStamps;

    protected $table = "persona";

    protected $fillable = [
        'primer_nombre',
        'segundo_nombre',
        'tercer_nombre',
        'primer_apellido',
        'segundo_apellido',
        'tercer_apellido',
        'fecha_nacimiento',
        'sexo_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class);
    }

    public function documentos(): BelongsToMany
    {
        return $this->belongsToMany(Documento::class, 'documento.persona_documento', 'persona_id', 'documento_id');
    }

    public function estudios(): HasMany
    {
        return $this->hasMany(Estudio::class);
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'persona_usuario', 'persona_id', 'usuario_id');
    }

    public function aspirantes(): HasMany
    {
        return $this->hasMany(Aspirante::class)->chaperone();
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class);
    }
}
