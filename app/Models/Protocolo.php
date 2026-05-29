<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'orden', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function medios()
    {
        return $this->hasMany(ProtocoloMedio::class)->orderBy('orden');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
