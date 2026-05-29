<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-warm-900">{{ __('Mis Citas') }}</h1>
                <p class="text-warm-400 text-sm mt-1">Gestiona reservas, filtros y estados.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('citas.calendario') }}" class="bg-rose-light/30 text-rose-dark font-bold py-3 px-6 rounded-2xl text-xs uppercase tracking-widest hover:bg-rose-light/50 transition">Calendario</a>
                @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <a href="{{ route('citas.agenda') }}" class="bg-cream text-warm-700 font-bold py-3 px-6 rounded-2xl text-xs uppercase tracking-widest hover:bg-warm-200 transition">Agenda</a>
                @endif
                <a href="{{ route('citas.create') }}" class="bg-rose-primary text-white font-bold py-3 px-8 rounded-2xl text-xs uppercase tracking-widest hover:bg-rose-dark transition shadow-sm">Nueva Cita</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 alert-success font-bold">{{ session('success') }}</div>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border border-warm-200 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="label">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Cliente o estado" class="mt-1 block w-full rounded-xl border-warm-200 bg-cream focus:ring-rose-primary focus:border-rose-primary">
                </div>
                <div>
                    <label class="label">Fecha</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}" class="mt-1 block rounded-xl border-warm-200 bg-cream focus:ring-rose-primary focus:border-rose-primary">
                </div>
                <div>
                    <label class="label">Estado</label>
                    <select name="estado" class="mt-1 block rounded-xl border-warm-200 bg-cream focus:ring-rose-primary focus:border-rose-primary">
                        <option value="">Todos</option>
                        @foreach(['pendiente','confirmada','completada','cancelada'] as $e)
                            <option value="{{ $e }}" @selected(request('estado') === $e)>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-warm-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase hover:bg-warm-800 transition">Filtrar</button>
            </form>
            @endif

            <div class="bg-white rounded-[2.5rem] border border-warm-200 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-cream">
                            <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-widest">Fecha / Hora</th>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-widest">Cliente</th>
                            @endif
                            <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-widest">Servicios</th>
                            <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-widest">Total</th>
                            <th class="px-8 py-5 text-[10px] font-black text-warm-400 uppercase tracking-widest">Estado</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-warm-400 uppercase tracking-widest">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-warm-100">
                        @forelse($citas as $cita)
                        <tr class="hover:bg-cream transition-colors">
                            <td class="px-8 py-5 font-bold text-warm-900">{{ $cita->fecha->format('d/m/Y') }}<br><span class="text-warm-400 text-sm">{{ substr($cita->hora,0,5) }}</span></td>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <td class="px-8 py-5 text-warm-700">{{ $cita->usuario?->name ?? '—' }}</td>
                            @endif
                            <td class="px-8 py-5 text-sm text-warm-600">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</td>
                            <td class="px-8 py-5 font-black text-rose-dark">${{ number_format($cita->total, 0) }}</td>
                            <td class="px-8 py-5">
                                @php
                                    $estadoClases = match($cita->estado) {
                                        'confirmada'  => 'bg-rose-light/30 text-rose-deeper',
                                        'completada'  => 'bg-gold-light/30 text-gold-deeper',
                                        'cancelada'   => 'bg-red-50 text-red-500',
                                        default       => 'bg-warm-100 text-warm-600',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $estadoClases }}">{{ $cita->estado }}</span>
                            </td>
                            <td class="px-8 py-5 text-right space-x-2">
                                <a href="{{ route('citas.show', $cita) }}" class="text-rose-dark font-bold text-xs hover:underline">Ver</a>
                                @if($cita->estado !== 'cancelada')
                                <a href="{{ route('citas.edit', $cita) }}" class="text-warm-600 font-bold text-xs hover:underline">Editar</a>
                                @endif
                                @if($cita->puedeResenar() && $cita->user_id === auth()->id())
                                <a href="{{ route('resenas.create', $cita) }}" class="text-gold-dark font-bold text-xs hover:underline">Reseñar</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-8 py-12 text-center text-warm-400 font-medium">No hay citas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-6">{{ $citas->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
