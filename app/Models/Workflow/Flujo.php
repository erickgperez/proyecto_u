<?php

namespace App\Models\Workflow;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flujo extends Model
{
    use UserStamps;

    protected $table = 'workflow.flujo';

    protected $fillable = [
        'codigo',
        'nombre',
        'activo',
        'tipo_flujo_id'
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoFlujo::class, 'tipo_flujo_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Obtiene las etapas relacionadas con el flujo.
     */
    public function etapas(): HasMany
    {
        return $this->hasMany(Etapa::class);
    }

    /**
     * Obtiene la primera etapa del flujo.
     *
     * @return Etapa|null La primera etapa o null si no hay etapas.
     */
    public function primeraEtapa(): ?Etapa
    {
        // Usamos 'first()' para obtener la primera instancia, o null si no hay ninguna
        return $this->etapas()->whereNotIn('id', function ($query) {
            $query->select('etapa_destino_id')
                ->from('workflow.transicion');
        })->first();
    }
}
