<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 italic">{{ __('Reservar Cita') }}</h1>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @include('citas._calendario_reserva', ['fechaInicial' => request('fecha', old('fecha', now()->format('Y-m-d')))])
            @include('citas._form', [
                'cita' => null,
                'action' => route('citas.store'),
                'method' => 'POST',
                'fechaInicial' => request('fecha', old('fecha', now()->format('Y-m-d'))),
            ])
        </div>
    </div>
</x-app-layout>
