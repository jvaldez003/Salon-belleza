<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    public $timestamps = false;

    protected $fillable = ['dia', 'hora_apertura', 'hora_cierre', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    private static array $nombres = [
        1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
        4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo',
    ];

    public static function delDia(int $dia): ?self
    {
        return self::where('dia', $dia)->first();
    }

    public static function nombreDia(int $dia): string
    {
        return self::$nombres[$dia] ?? '';
    }

    public function getNombreAttribute(): string
    {
        return self::$nombres[$this->dia] ?? '';
    }
}
