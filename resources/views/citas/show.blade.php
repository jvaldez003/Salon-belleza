<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 italic">Detalle de Cita #{{ $cita->id }}</h1>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-4">
                <p><span class="text-slate-400 text-xs font-black uppercase">Fecha</span><br><strong>{{ $cita->fecha->format('d/m/Y') }} {{ substr($cita->hora,0,5) }}</strong></p>
                <p><span class="text-slate-400 text-xs font-black uppercase">Cliente</span><br><strong>{{ $cita->usuario?->name }}</strong></p>
                <p><span class="text-slate-400 text-xs font-black uppercase">Servicios</span><br>{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                <p><span class="text-slate-400 text-xs font-black uppercase">Total</span><br><strong class="text-indigo-600 text-2xl">${{ number_format($cita->total, 0) }}</strong></p>
                <p><span class="text-slate-400 text-xs font-black uppercase">Estado</span><br>{{ ucfirst($cita->estado) }}</p>
                @if($cita->notas)<p><span class="text-slate-400 text-xs font-black uppercase">Notas</span><br>{{ $cita->notas }}</p>@endif
                <div class="pt-6 flex gap-3">
                    <a href="{{ route('citas.index') }}" class="text-slate-600 font-bold">← Volver</a>
                    @if($cita->estado !== 'cancelada')
                    <a href="{{ route('citas.edit', $cita) }}" class="text-indigo-600 font-bold">Editar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
