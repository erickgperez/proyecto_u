<?php

namespace App\Models\PlanEstudio;

use App\Models\User;
use App\Traits\HasCreateMany;
use App\Traits\HasUuid;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class TipoRequisito extends Model
{
    use UserStamps, HasUuid, HasCreateMany;

    protected $table = 'plan_estudio.tipo_requisito';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
