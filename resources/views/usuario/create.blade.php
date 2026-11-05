<x-app-layout titulo="Nueva cuenta">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('usuario.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Usuarios
            </a>
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Nueva cuenta</h1>
            <p class="text-texto-suave max-w-prose">Los clientes pueden crear su cuenta solos al reservar. Usa esto para el equipo o para registrar a alguien que reserva por teléfono.</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 md:py-10">
        <form method="POST" action="{{ route('usuario.store') }}" class="card p-6 md:p-8 grid gap-8">
            @csrf
            @include('usuario._form', ['user' => null])
            <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-linea">
                <button type="submit" class="btn-primary">Crear cuenta</button>
                <a href="{{ route('usuario.index') }}" class="btn text-texto hover:text-oceano">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
