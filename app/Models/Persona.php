<?php

namespace App\Models;

use App\Models\Academico\Docente;
use App\Models\Documento\Documento;
use App\Models\Ingreso\Aspirante;
use App\Models\Sexo;
use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

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

    protected $appends = ['nombre', 'apellidos', 'edad', 'nombreCompleto', 'nombreCorto'];

    public function getNombreAttribute(): string
    {
        $unido = "{$this->primer_nombre} {$this->segundo_nombre} {$this->tercer_nombre}";
        return (string) trim(preg_replace('/\s+/', ' ', $unido));
    }

    public function getApellidosAttribute(): string
    {
        $unido = "{$this->primer_apellido} {$this->segundo_apellido} {$this->tercer_apellido}";
        return (string) trim(preg_replace('/\s+/', ' ', $unido));
    }

    public function getNombreCompletoAttribute(): string
    {
        $unido = "{$this->primer_nombre} {$this->segundo_nombre} {$this->tercer_nombre} {$this->primer_apellido} {$this->segundo_apellido} {$this->tercer_apellido}";
        return (string) trim(preg_replace('/\s+/', ' ', $unido));
    }

    public function getNombreCortoAttribute(): string
    {
        $unido = "{$this->primer_nombre} {$this->primer_apellido}";
        return (string) trim(preg_replace('/\s+/', ' ', $unido));
    }

    public function getEdadAttribute(): string
    {
        $edad = '';
        if ($this->fecha_nacimiento !== null) {
            $hoy = new \DateTime();
            $fecha_nacimiento = new \DateTime($this->fecha_nacimiento);

            $intervalo = $hoy->diff($fecha_nacimiento);

            // Extrae el número de años de la diferencia
            $edad = $intervalo->y;
        }

        return $edad;
    }

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

    public function aspirante(): HasOne
    {
        return $this->hasOne(Aspirante::class, 'persona_id');
    }

    public function datosContacto(): HasOne
    {
        return $this->hasOne(DatosContacto::class, 'persona_id');
    }

    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class, 'persona_id');
    }
}
