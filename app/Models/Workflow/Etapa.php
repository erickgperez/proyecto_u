<?php

namespace App\Models\Workflow;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etapa extends Model
{
    use UserStamps;

    protected $table = 'workflow.etapa';

    protected $fillable = [
        'codigo',
        'nombre',
        'indicaciones',
        'flujo_id',
        'lugar_id',
    ];

    public function flujo(): BelongsTo
    {
        return $this->belongsTo(Flujo::class, 'flujo_id');
    }

    public function lugar(): BelongsTo
    {
        return $this->belongsTo(Lugar::class, 'flujo_id');
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
