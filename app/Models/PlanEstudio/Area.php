<?php

namespace App\Models\PlanEstudio;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;


class Area extends Model
{

    use UserStamps;

    protected $table = 'plan_estudio.area';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
