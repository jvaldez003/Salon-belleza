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
    protected $fillable = ['nombre', 'precio', 'descripcion', 'duracion', 'activo', 'categoria_id'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación Uno a Muchos
     * 
     * Un servicio puede tener varias imágenes (carrusel).
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ServicioImagen::class);
    }

    public function citas()
    {
        return $this->belongsToMany(Cita::class, 'cita_servicio');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'empleado_servicio');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Nombre para mostrar al público: sin MAYÚSCULAS sostenidas y sin la palabra "desde".
     * "Cepillado y Planchado desde" → "Cepillado y Planchado"
     */
    public function getNombrePublicoAttribute(): string
    {
        $nombre = \Illuminate\Support\Str::legible($this->nombre);
        $nombre = trim(preg_replace('/\s*\bdesde\b\s*/iu', ' ', $nombre));
        return \Illuminate\Support\Str::ucfirst($nombre);
    }

    /** true si el precio es "desde" (el nombre del servicio lo indica). */
    public function getPrecioDesdeAttribute(): bool
    {
        return (bool) preg_match('/\bdesde\b/iu', (string) $this->nombre);
    }

    /** Duración legible: 60 → "1 h", 90 → "1 h 30 min", 45 → "45 min". */
    public function getDuracionTextoAttribute(): string
    {
        $min = (int) $this->duracion;
        if ($min < 60) return "{$min} min";
        $h = intdiv($min, 60);
        $m = $min % 60;
        return $m ? "{$h} h {$m} min" : "{$h} h";
    }
}
