<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionSitio extends Model
{
    protected $table = 'configuracion_sitio';

    protected $fillable = [
        'nombre_negocio', 'eslogan', 'logo',
        'quienes_somos_titulo', 'quienes_somos_texto', 'quienes_somos_imagen',
        'mision_texto', 'mision_imagen',
        'vision_texto', 'vision_imagen',
        'telefono', 'email_contacto', 'direccion',
        'whatsapp', 'facebook', 'instagram', 'tiktok', 'youtube',
    ];

    /**
     * Número de WhatsApp listo para enlaces wa.me (solo dígitos, con indicativo de país).
     * Si se guardó un celular colombiano sin indicativo (10 dígitos que empiezan por 3), se le agrega el 57.
     * "310 889 9452" → "573108899452". Devuelve null si no hay número.
     */
    public function getWhatsappNumeroAttribute(): ?string
    {
        $digitos = preg_replace('/[^0-9]/', '', (string) $this->whatsapp);
        if ($digitos === '') {
            return null;
        }
        if (strlen($digitos) === 10 && str_starts_with($digitos, '3')) {
            $digitos = '57' . $digitos;
        }
        return $digitos;
    }

    /** Devuelve siempre el único registro, creándolo si no existe. */
    public static function instancia(): self
    {
        return self::firstOrCreate([], ['nombre_negocio' => 'Arrecife']);
    }
}
