<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * @param string|null $titulo Título de la pestaña del navegador (p. ej. "Entrar").
     */
    public function __construct(public ?string $titulo = null)
    {
    }

    public function render(): View
    {
        return view('layouts.guest');
    }
}
