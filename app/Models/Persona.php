<?php

namespace App\Models;

use App\Models\Sexo;
use App\Models\User;
use App\Traits\UserStamps;
use Illuminate\Database\Eloquent\Model;

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
}
