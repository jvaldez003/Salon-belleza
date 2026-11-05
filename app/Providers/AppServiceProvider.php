<?php

namespace App\Providers;

use App\Models\ConfiguracionSitio;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('local') && ! $this->app->runningInConsole()) {
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
        }

        // Comparte la configuración del sitio y flags de navegación con todas las vistas
        if (Schema::hasTable('configuracion_sitio')) {
            $conf = ConfiguracionSitio::instancia();
            View::share('configuracionSitio', $conf);

            $tieneNosotros = $conf && (
                $conf->quienes_somos_texto || $conf->mision_texto || $conf->vision_texto ||
                !empty($conf->quienes_somos_imagen) || !empty($conf->mision_imagen) || !empty($conf->vision_imagen)
            );
            View::share('tieneNosotros', $tieneNosotros);
        }

        if (Schema::hasTable('protocolos')) {
            View::share('tieneProtocolos', \App\Models\Protocolo::where('activo', true)->exists());
        }

        /*
         | Str::legible(): convierte textos guardados en MAYÚSCULAS a formato de oración.
         | "SOMOS UN STUDIO. UN ESPACIO EXCLUSIVO" → "Somos un studio. Un espacio exclusivo"
         | Si el texto ya tiene mayúsculas y minúsculas mezcladas, lo deja igual.
         | Uso en Blade: {{ Str::legible($categoria->nombre) }}
         */
        Str::macro('legible', function (?string $texto): string {
            $texto = trim((string) $texto);
            if ($texto === '') {
                return '';
            }
            $letras = preg_replace('/[^\p{L}]/u', '', $texto);
            $mayus  = preg_replace('/[^\p{Lu}]/u', '', $letras);
            if (mb_strlen($letras) === 0 || mb_strlen($mayus) / mb_strlen($letras) < 0.7) {
                return $texto;
            }
            $texto = mb_strtolower(str_replace("\r\n", "\n", $texto));
            // Un salto de línea suelto suele cortar una frase a la mitad: se une con un espacio.
            // Las líneas en blanco (párrafos) se conservan.
            $texto = preg_replace('/[ \t]*(?<!\n)\n(?!\n)[ \t]*/u', ' ', $texto);
            // Mayúscula al inicio, después de cierre de oración y al empezar un párrafo
            $texto = preg_replace_callback(
                '/(^|[.!?]\s+|[¡¿]|\n\n\s*)(\p{Ll})/u',
                fn ($m) => $m[1] . mb_strtoupper($m[2]),
                $texto
            );
            // Nombres propios que siempre van con mayúscula inicial (los gentilicios, como "candelareños", van en minúscula)
            foreach (['Arrecife Beauty Studio', 'Arrecife', 'Candelaria'] as $nombre) {
                $texto = preg_replace('/\b' . preg_quote(mb_strtolower($nombre), '/') . '\b/u', $nombre, $texto);
            }
            return $texto;
        });

        // A dónde lleva "Reservar cita" en las páginas públicas:
        // con sesión iniciada, al formulario de citas; sin sesión, al registro.
        View::composer(['layouts.public', 'welcome', 'nosotros', 'protocolos-publico', 'citas.reservar'], function ($view) {
            $view->with('urlReserva', auth()->check()
                ? route('citas.create')
                : (\Illuminate\Support\Facades\Route::has('register') ? route('register', ['reservar' => 1]) : route('citas.create')));
        });

        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        Blade::if('editor', function () {
            return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isEditor());
        });
    }
}
