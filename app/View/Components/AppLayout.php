<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * @param string|null $titulo Título de la pestaña del navegador (p. ej. "Agenda").
     */
    public function __construct(public ?string $titulo = null)
    {
    }

    public function render(): View
    {
        return view('layouts.app');
    }
}
