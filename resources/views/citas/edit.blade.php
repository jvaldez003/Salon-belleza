<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 italic">{{ __('Editar Cita') }}</h1>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('citas._form', ['cita' => $cita, 'action' => route('citas.update', $cita), 'method' => 'PUT'])
        </div>
    </div>
</x-app-layout>
