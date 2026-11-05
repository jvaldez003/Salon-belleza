<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaImagen extends Model
{
    protected $table = 'categoria_imagenes';
    protected $fillable = ['categoria_id', 'url', 'orden'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}
