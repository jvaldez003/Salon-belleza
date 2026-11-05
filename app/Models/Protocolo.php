<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Protocolo extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'orden', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function medios()
    {
        return $this->hasMany(ProtocoloMedio::class)->orderBy('orden');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }

    /** Nombre para mostrar: sin MAYÚSCULAS sostenidas. */
    public function getNombrePublicoAttribute(): string
    {
        return Str::ucfirst(Str::legible($this->nombre));
    }

    /**
     * Lee la descripción (pegada desde Word, con viñetas "•" y en MAYÚSCULAS) y la ordena en partes
     * para la ficha pública y la vista previa del admin:
     *   intro         primera línea sin etiqueta ("Tratamiento hidratante (no alisa) que…")
     *   pasos         la línea "INCLUYE: a, b, c" separada por comas
     *   duracion      la línea que empieza por "DURACIÓN" → "Aprox. dos horas"
     *   duracionNota  lo que va entre paréntesis en esa línea
     *   notas         todo lo demás
     * Si la descripción no sigue ese formato, todo queda en notas: nunca se pierde texto.
     */
    public function getFichaAttribute(): array
    {
        $limpiar = fn ($l) => trim(preg_replace(['/\(\s+/u', '/\s+\)/u', '/\s{2,}/u'], ['(', ')', ' '], Str::legible($l)));

        $lineas = collect(preg_split('/\R/u', (string) $this->descripcion))
            ->map(fn ($l) => trim(preg_replace('/^[\s•·\-\*–—▪●◦]+/u', '', $l)))
            ->filter()
            ->map($limpiar)
            ->values();

        $ficha = ['intro' => null, 'pasos' => [], 'duracion' => null, 'duracionNota' => null, 'notas' => []];

        foreach ($lineas as $l) {
            if (preg_match('/^incluye\s*:?\s*(.+)$/iu', $l, $m)) {
                $ficha['pasos'] = collect(preg_split('/\s*,\s*/u', rtrim($m[1], '. ')))
                    ->map(fn ($p) => Str::ucfirst(trim($p)))->filter()->values()->all();
            } elseif (preg_match('/^duraci[oó]n\s*:?\s*(?:aprox(?:imada(?:mente)?)?\.?)?\s*(.+)$/iu', $l, $m)) {
                $valor = trim($m[1]);
                if (preg_match('/^(.*?)\s*\(([^)]+)\)\s*$/u', $valor, $p)) {
                    $valor = $p[1];
                    $ficha['duracionNota'] = Str::ucfirst($p[2]);
                }
                $ficha['duracion'] = 'Aprox. ' . $valor;
            } elseif (! $ficha['intro'] && ! str_contains($l, ':')) {
                $ficha['intro'] = Str::ucfirst($l);
            } else {
                $ficha['notas'][] = Str::ucfirst($l);
            }
        }

        return $ficha;
    }
}
