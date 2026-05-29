<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'hora',
        'user_id',
        'total',
        'estado',
        'notas',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'cita_servicio');
    }

    public function resena()
    {
        return $this->hasOne(Resena::class);
    }

    public function puedeResenar(): bool
    {
        return $this->estado === 'completada' && ! $this->resena;
    }

    public function duracionMinutos(): int
    {
        $duracion = $this->servicios->sum('duracion');

        return $duracion > 0 ? $duracion : 60;
    }

    public function horaInicio(): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($this->fecha->format('Y-m-d').' '.$this->hora);
    }

    public function horaFin(): \Carbon\Carbon
    {
        return $this->horaInicio()->copy()->addMinutes($this->duracionMinutos());
    }

    public function scopeActivas($query)
    {
        return $query->whereNotIn('estado', ['cancelada']);
    }

    public function scopeDelDia($query, $fecha = null)
    {
        return $query->whereDate('fecha', $fecha ?? now()->toDateString());
    }
}
