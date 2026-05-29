<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-warm-900">Agenda del Salón</h1>
                <p class="text-warm-400 text-sm mt-1">
                    @if($vista === 'semana')
                        Semana del {{ $inicioSemana->format('d/m/Y') }} al {{ $finSemana->format('d/m/Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd D [de] MMMM YYYY') }}
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex bg-cream rounded-2xl p-1">
                    <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => ($fechaBase ?? \Carbon\Carbon::parse($fecha))->format('Y-m-d')]) }}"
                       class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $vista === 'dia' ? 'bg-white text-rose-dark shadow-sm' : 'text-warm-500 hover:text-warm-800' }}">
                        Día
                    </a>
                    <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => ($fechaBase ?? \Carbon\Carbon::parse($fecha))->format('Y-m-d')]) }}"
                       class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $vista === 'semana' ? 'bg-white text-rose-dark shadow-sm' : 'text-warm-500 hover:text-warm-800' }}">
                        Semana
                    </a>
                </div>
                <a href="{{ route('citas.calendario') }}" class="bg-rose-light/20 text-rose-dark font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-widest hover:bg-rose-light/40 transition">Calendario</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border border-warm-200 flex flex-wrap gap-4 items-end">
                <input type="hidden" name="vista" value="{{ $vista }}">
                <div>
                    <label class="label">
                        {{ $vista === 'semana' ? 'Semana (cualquier día)' : 'Fecha' }}
                    </label>
                    <input type="date" name="fecha" value="{{ ($fechaBase ?? \Carbon\Carbon::parse($fecha))->format('Y-m-d') }}" class="mt-1 block rounded-xl border-warm-200 bg-cream focus:ring-rose-primary">
                </div>
                <div class="flex gap-2">
                    @php $ref = ($fechaBase ?? \Carbon\Carbon::parse($fecha)); @endphp
                    @if($vista === 'semana')
                        <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => $ref->copy()->subWeek()->format('Y-m-d')]) }}" class="bg-cream px-4 py-2.5 rounded-xl font-bold text-xs text-warm-700 hover:bg-warm-200 transition">← Sem. ant.</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => now()->format('Y-m-d')]) }}" class="bg-cream px-4 py-2.5 rounded-xl font-bold text-xs text-warm-700 hover:bg-warm-200 transition">Hoy</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => $ref->copy()->addWeek()->format('Y-m-d')]) }}" class="bg-cream px-4 py-2.5 rounded-xl font-bold text-xs text-warm-700 hover:bg-warm-200 transition">Sem. sig. →</a>
                    @else
                        <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => $ref->copy()->subDay()->format('Y-m-d')]) }}" class="bg-cream px-4 py-2.5 rounded-xl font-bold text-xs text-warm-700 hover:bg-warm-200 transition">← Ayer</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => now()->format('Y-m-d')]) }}" class="bg-cream px-4 py-2.5 rounded-xl font-bold text-xs text-warm-700 hover:bg-warm-200 transition">Hoy</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => $ref->copy()->addDay()->format('Y-m-d')]) }}" class="bg-cream px-4 py-2.5 rounded-xl font-bold text-xs text-warm-700 hover:bg-warm-200 transition">Mañana →</a>
                    @endif
                </div>
                <button type="submit" class="bg-warm-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase hover:bg-warm-800 transition">Ver</button>
            </form>

            @if($vista === 'semana')
                <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                    @foreach($diasSemana as $dia)
                        @php
                            $clave = $dia->format('Y-m-d');
                            $citasDelDia = $citas->get($clave, collect());
                            $esHoy = $dia->isToday();
                            $esDomingo = $dia->dayOfWeekIso === 7;
                        @endphp
                        <div class="bg-white rounded-3xl border {{ $esHoy ? 'border-rose-primary ring-2 ring-rose-light/30' : 'border-warm-200' }} overflow-hidden min-h-[280px]">
                            <div class="px-4 py-3 border-b border-warm-100 {{ $esHoy ? 'bg-rose-light/15' : 'bg-cream' }}">
                                <p class="text-[10px] font-black uppercase tracking-widest text-warm-400">{{ $dia->locale('es')->isoFormat('ddd') }}</p>
                                <p class="font-black text-lg {{ $esHoy ? 'text-rose-dark' : 'text-warm-900' }}">{{ $dia->format('d/m') }}</p>
                            </div>
                            <div class="p-3 space-y-2">
                                @if($esDomingo)
                                    <p class="text-[10px] font-bold text-warm-300 uppercase text-center py-6">Cerrado</p>
                                @elseif($citasDelDia->isEmpty())
                                    <p class="text-[10px] font-bold text-warm-300 uppercase text-center py-6">Sin citas</p>
                                @else
                                    @foreach($citasDelDia as $cita)
                                        <a href="{{ route('citas.show', $cita) }}" class="block p-3 rounded-2xl bg-cream hover:bg-rose-light/15 border border-warm-200 hover:border-rose-light transition">
                                            <p class="font-black text-sm text-rose-dark">{{ substr($cita->hora, 0, 5) }}</p>
                                            <p class="font-bold text-xs text-warm-800 truncate">{{ $cita->usuario?->name ?? '—' }}</p>
                                            <p class="text-[10px] text-warm-500 truncate">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="space-y-4">
                    @php $totalDia = ($citasDia ?? collect())->sum('total'); @endphp
                    <div class="bg-white p-6 rounded-3xl border border-warm-200 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase text-warm-400 tracking-widest">Resumen del día</p>
                            <p class="font-black text-2xl text-warm-900">{{ ($citasDia ?? collect())->count() }} citas</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase text-warm-400 tracking-widest">Ingresos estimados</p>
                            <p class="font-black text-2xl text-rose-dark">${{ number_format($totalDia, 0) }}</p>
                        </div>
                    </div>

                    @forelse($citasDia ?? [] as $cita)
                        <div class="bg-white p-6 rounded-3xl border border-warm-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-rose-light transition">
                            <div class="flex items-start gap-4">
                                <div class="bg-rose-light/20 text-rose-dark font-black text-lg px-4 py-3 rounded-2xl min-w-[80px] text-center">
                                    {{ substr($cita->hora, 0, 5) }}
                                </div>
                                <div>
                                    <p class="font-black text-lg text-warm-900">{{ $cita->usuario?->name ?? '—' }}</p>
                                    <p class="text-sm text-warm-500">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-warm-100 text-warm-600">{{ $cita->estado }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 sm:text-right w-full sm:w-auto justify-between sm:justify-end">
                                <p class="font-black text-rose-dark text-xl">${{ number_format($cita->total, 0) }}</p>
                                <a href="{{ route('citas.show', $cita) }}" class="text-rose-dark font-bold text-xs uppercase tracking-widest hover:underline">Ver →</a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-12 rounded-3xl border border-warm-200 text-center">
                            <p class="text-warm-400 font-medium">Sin citas programadas para este día.</p>
                            <a href="{{ route('citas.calendario') }}" class="inline-block mt-4 text-rose-dark font-bold text-sm hover:underline">Ver calendario completo</a>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
