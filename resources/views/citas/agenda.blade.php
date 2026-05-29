<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-slate-900 italic">Agenda del Salón</h1>
                <p class="text-slate-500 text-sm mt-1">
                    @if($vista === 'semana')
                        Semana del {{ $inicioSemana->format('d/m/Y') }} al {{ $finSemana->format('d/m/Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($fecha)->locale('es')->isoFormat('dddd D [de] MMMM YYYY') }}
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex bg-slate-100 rounded-2xl p-1">
                    <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => ($fechaBase ?? \Carbon\Carbon::parse($fecha))->format('Y-m-d')]) }}"
                       class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $vista === 'dia' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Día
                    </a>
                    <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => ($fechaBase ?? \Carbon\Carbon::parse($fecha))->format('Y-m-d')]) }}"
                       class="px-5 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition {{ $vista === 'semana' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        Semana
                    </a>
                </div>
                <a href="{{ route('citas.calendario') }}" class="bg-indigo-50 text-indigo-700 font-bold py-2.5 px-5 rounded-xl text-xs uppercase tracking-widest hover:bg-indigo-100 transition">Calendario</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border border-slate-100 flex flex-wrap gap-4 items-end">
                <input type="hidden" name="vista" value="{{ $vista }}">
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">
                        {{ $vista === 'semana' ? 'Semana (cualquier día)' : 'Fecha' }}
                    </label>
                    <input type="date" name="fecha" value="{{ ($fechaBase ?? \Carbon\Carbon::parse($fecha))->format('Y-m-d') }}" class="mt-1 block rounded-xl border-slate-200">
                </div>
                <div class="flex gap-2">
                    @php $ref = ($fechaBase ?? \Carbon\Carbon::parse($fecha)); @endphp
                    @if($vista === 'semana')
                        <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => $ref->copy()->subWeek()->format('Y-m-d')]) }}" class="bg-slate-100 px-4 py-2.5 rounded-xl font-bold text-xs">← Sem. ant.</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => now()->format('Y-m-d')]) }}" class="bg-slate-100 px-4 py-2.5 rounded-xl font-bold text-xs">Hoy</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'semana', 'fecha' => $ref->copy()->addWeek()->format('Y-m-d')]) }}" class="bg-slate-100 px-4 py-2.5 rounded-xl font-bold text-xs">Sem. sig. →</a>
                    @else
                        <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => $ref->copy()->subDay()->format('Y-m-d')]) }}" class="bg-slate-100 px-4 py-2.5 rounded-xl font-bold text-xs">← Ayer</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => now()->format('Y-m-d')]) }}" class="bg-slate-100 px-4 py-2.5 rounded-xl font-bold text-xs">Hoy</a>
                        <a href="{{ route('citas.agenda', ['vista' => 'dia', 'fecha' => $ref->copy()->addDay()->format('Y-m-d')]) }}" class="bg-slate-100 px-4 py-2.5 rounded-xl font-bold text-xs">Mañana →</a>
                    @endif
                </div>
                <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase">Ver</button>
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
                        <div class="bg-white rounded-3xl border {{ $esHoy ? 'border-indigo-300 ring-2 ring-indigo-100' : 'border-slate-100' }} overflow-hidden min-h-[280px]">
                            <div class="px-4 py-3 border-b border-slate-50 {{ $esHoy ? 'bg-indigo-50' : 'bg-slate-50/50' }}">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $dia->locale('es')->isoFormat('ddd') }}</p>
                                <p class="font-black text-lg {{ $esHoy ? 'text-indigo-600' : 'text-slate-900' }}">{{ $dia->format('d/m') }}</p>
                            </div>
                            <div class="p-3 space-y-2">
                                @if($esDomingo)
                                    <p class="text-[10px] font-bold text-slate-400 uppercase text-center py-6">Cerrado</p>
                                @elseif($citasDelDia->isEmpty())
                                    <p class="text-[10px] font-bold text-slate-300 uppercase text-center py-6">Sin citas</p>
                                @else
                                    @foreach($citasDelDia as $cita)
                                        <a href="{{ route('citas.show', $cita) }}" class="block p-3 rounded-2xl bg-slate-50 hover:bg-indigo-50 border border-slate-100 hover:border-indigo-200 transition">
                                            <p class="font-black text-sm text-indigo-600">{{ substr($cita->hora, 0, 5) }}</p>
                                            <p class="font-bold text-xs text-slate-800 truncate">{{ $cita->usuario?->name ?? '—' }}</p>
                                            <p class="text-[10px] text-slate-500 truncate">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
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
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Resumen del día</p>
                            <p class="font-black text-2xl text-slate-900">{{ ($citasDia ?? collect())->count() }} citas</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Ingresos estimados</p>
                            <p class="font-black text-2xl text-indigo-600">${{ number_format($totalDia, 0) }}</p>
                        </div>
                    </div>

                    @forelse($citasDia ?? [] as $cita)
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-indigo-200 transition">
                            <div class="flex items-start gap-4">
                                <div class="bg-indigo-50 text-indigo-600 font-black text-lg px-4 py-3 rounded-2xl min-w-[80px] text-center">
                                    {{ substr($cita->hora, 0, 5) }}
                                </div>
                                <div>
                                    <p class="font-black text-lg">{{ $cita->usuario?->name ?? '—' }}</p>
                                    <p class="text-sm text-slate-500">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100">{{ $cita->estado }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 sm:text-right w-full sm:w-auto justify-between sm:justify-end">
                                <p class="font-black text-indigo-600 text-xl">${{ number_format($cita->total, 0) }}</p>
                                <a href="{{ route('citas.show', $cita) }}" class="text-indigo-600 font-bold text-xs uppercase tracking-widest">Ver →</a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-12 rounded-3xl border border-slate-100 text-center">
                            <p class="text-slate-400 font-medium">Sin citas programadas para este día.</p>
                            <a href="{{ route('citas.calendario') }}" class="inline-block mt-4 text-indigo-600 font-bold text-sm">Ver calendario completo</a>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
