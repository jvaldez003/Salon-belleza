<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre', 'imagen', 'orden', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function imagenes()
    {
        return $this->hasMany(CategoriaImagen::class)->orderBy('orden');
    }

    public function scopeActivas($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
