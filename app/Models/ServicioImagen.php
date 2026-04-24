<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioImagen extends Model
{
    protected $table = 'servicio_imagenes';
    protected $fillable = ['servicio_id', 'url'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
