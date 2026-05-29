<?php

namespace App\Providers;

use App\Models\ConfiguracionSitio;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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

        // Comparte la configuración del sitio con todas las vistas
        if (Schema::hasTable('configuracion_sitio')) {
            View::share('configuracionSitio', ConfiguracionSitio::instancia());
        }

        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->isAdmin();
        });

        Blade::if('editor', function () {
            return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isEditor());
        });
    }
}
