<?php

namespace App\Models\Workflow;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class TipoFlujo extends Model
{
    use UserStamps;

    protected $table = 'workflow.tipo_flujo';

    protected $fillable = [
        'codigo',
        'descripcion'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
