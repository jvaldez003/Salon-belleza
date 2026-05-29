<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $fillable = ['nombre', 'especialidad', 'foto', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'empleado_servicio');
    }

    public function horarios()
    {
        return $this->hasMany(HorarioEmpleado::class)->orderBy('dia');
    }

    public function horarioDelDia(int $dia): ?HorarioEmpleado
    {
        return $this->horarios->firstWhere('dia', $dia);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
