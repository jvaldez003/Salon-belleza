<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-warm-900">Detalle de Cita #{{ $cita->id }}</h1>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 rounded-[2.5rem] border border-warm-200 shadow-sm space-y-6">
                <div class="space-y-1">
                    <span class="label">Fecha</span>
                    <p class="font-bold text-warm-900 text-lg">{{ $cita->fecha->format('d/m/Y') }} · {{ substr($cita->hora,0,5) }}</p>
                </div>
                <div class="space-y-1">
                    <span class="label">Cliente</span>
                    <p class="font-bold text-warm-900">{{ $cita->usuario?->name }}</p>
                </div>
                <div class="space-y-1">
                    <span class="label">Servicios</span>
                    <p class="text-warm-700">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                </div>
                <div class="space-y-1">
                    <span class="label">Total</span>
                    <p class="font-black text-rose-dark text-2xl">${{ number_format($cita->total, 0) }}</p>
                </div>
                <div class="space-y-1">
                    <span class="label">Estado</span>
                    <p class="font-bold text-warm-800">{{ ucfirst($cita->estado) }}</p>
                </div>
                @if($cita->notas)
                <div class="space-y-1">
                    <span class="label">Notas</span>
                    <p class="text-warm-600">{{ $cita->notas }}</p>
                </div>
                @endif
                <div class="pt-4 border-t border-warm-200 flex gap-4">
                    <a href="{{ route('citas.index') }}" class="text-warm-600 font-bold hover:text-warm-900 transition">← Volver</a>
                    @if($cita->estado !== 'cancelada')
                    <a href="{{ route('citas.edit', $cita) }}" class="text-rose-dark font-bold hover:text-rose-deeper transition">Editar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
