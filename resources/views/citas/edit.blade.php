{{-- Edición de cita desde el panel (equipo del salón). Las clientas usan citas/reservar. --}}
<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display text-3xl">Editar cita</h1>
    </x-slot>

    <div class="py-10 bg-superficie min-h-screen">
        <div class="max-w-arrecife mx-auto px-4 sm:px-6">
            @include('citas._form', ['cita' => $cita, 'action' => route('citas.update', $cita), 'method' => 'PUT'])
        </div>
    </div>
</x-app-layout>
