<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioEmpleado extends Model
{
    public $timestamps = false;

    protected $table = 'horario_empleado';

    protected $fillable = ['empleado_id', 'dia', 'hora_apertura', 'hora_cierre', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
