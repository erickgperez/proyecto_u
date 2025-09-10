<?php

namespace App\Models\Ingreso;

use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    use UserStamps;

    protected $table = "ingreso.convocatoria";

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha',
        'cuerpo_mensaje',
        'afiche'
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
