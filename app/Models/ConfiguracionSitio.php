<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionSitio extends Model
{
    protected $table = 'configuracion_sitio';

    protected $fillable = [
        'nombre_negocio',
        'logo',
        'quienes_somos_titulo',
        'quienes_somos_texto',
        'quienes_somos_imagen',
        'mision_texto',
        'mision_imagen',
        'vision_texto',
        'vision_imagen',
        'telefono',
        'email_contacto',
        'direccion',
    ];

    /** Devuelve siempre el único registro, creándolo si no existe. */
    public static function instancia(): self
    {
        return self::firstOrCreate([], ['nombre_negocio' => 'Arrecife']);
    }
}
