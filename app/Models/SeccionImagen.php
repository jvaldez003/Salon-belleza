<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeccionImagen extends Model
{
    protected $table = 'seccion_imagenes';

    protected $fillable = ['seccion', 'imagen_url', 'orden'];
}
