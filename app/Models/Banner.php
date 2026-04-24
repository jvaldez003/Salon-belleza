<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Banner
 * 
 * Gestiona las imágenes y textos destacados que aparecen en el carrusel (Hero)
 * de la página principal.
 */
class Banner extends Model
{
    use HasFactory;

    // Campos que permiten la asignación masiva para creación y edición rápida
    protected $fillable = [
        'titulo',       // Título principal del banner
        'subtitulo',    // Texto secundario informativo
        'imagen_url',   // Ruta de la imagen almacenada en el storage
        'texto_boton',  // Etiqueta del botón de acción
        'link_boton',   // URL a la que redirige el botón
        'activo'        // Estado de visibilidad (true = se muestra, false = oculto)
    ];
}
