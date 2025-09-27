<?php

namespace App\Models\Workflow;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flujo extends Model
{
    use UserStamps;

    protected $table = 'workflow.flujo';

    protected $fillable = [
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
}
