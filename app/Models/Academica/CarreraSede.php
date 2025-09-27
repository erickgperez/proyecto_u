<?php

namespace App\Models\Academica;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class CarreraSede extends Model
{
    use UserStamps;

    protected $table = "academico.carrera_sede";

    protected $fillable = [
        'carrera_id',
        'sede_id'
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
