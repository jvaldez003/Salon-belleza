<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProtocoloMedio extends Model
{
    protected $table = 'protocolo_medios';

    protected $fillable = ['protocolo_id', 'tipo', 'url', 'orden'];

    public function protocolo()
    {
        return $this->belongsTo(Protocolo::class);
    }

    /** Devuelve true si la URL es externa (YouTube / Vimeo). */
    public function esUrlExterna(): bool
    {
        return str_starts_with($this->url, 'http');
    }
}
