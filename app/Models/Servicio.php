<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Servicio
 * 
 * Representa un tratamiento de belleza en el catálogo del salón.
 * Gestiona la información básica del servicio y sus imágenes relacionadas.
 */
class Servicio extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'servicios';

    // Desactivamos timestamps automáticos (created_at, updated_at) ya que no se requieren en este modelo
    public $timestamps = false;

    // Campos que se pueden asignar masivamente desde formularios
    protected $fillable = ['nombre', 'precio', 'descripcion'];

    /**
     * Relación Uno a Muchos
     * 
     * Un servicio puede tener varias imágenes (carrusel).
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imagenes()
    {
        return $this->hasMany(ServicioImagen::class);
    }
}
