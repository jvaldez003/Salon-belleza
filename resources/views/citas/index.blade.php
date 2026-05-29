<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-slate-900 italic">{{ __('Mis Citas') }}</h1>
                <p class="text-slate-500 text-sm mt-1">Gestiona reservas, filtros y estados.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('citas.calendario') }}" class="bg-indigo-50 text-indigo-700 font-bold py-3 px-6 rounded-2xl text-xs uppercase tracking-widest hover:bg-indigo-100 transition">Calendario</a>
                @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <a href="{{ route('citas.agenda') }}" class="bg-slate-100 text-slate-800 font-bold py-3 px-6 rounded-2xl text-xs uppercase tracking-widest hover:bg-slate-200 transition">Agenda</a>
                @endif
                <a href="{{ route('citas.create') }}" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-2xl text-xs uppercase tracking-widest hover:bg-indigo-700 transition shadow-lg">Nueva Cita</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl font-bold">{{ session('success') }}</div>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border border-slate-100 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Buscar</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Cliente o estado" class="mt-1 block w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Fecha</label>
                    <input type="date" name="fecha" value="{{ request('fecha') }}" class="mt-1 block rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Estado</label>
                    <select name="estado" class="mt-1 block rounded-xl border-slate-200">
                        <option value="">Todos</option>
                        @foreach(['pendiente','confirmada','completada','cancelada'] as $e)
                            <option value="{{ $e }}" @selected(request('estado') === $e)>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase">Filtrar</button>
            </form>
            @endif

            <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fecha / Hora</th>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cliente</th>
                            @endif
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Servicios</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Estado</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($citas as $cita)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-8 py-5 font-bold">{{ $cita->fecha->format('d/m/Y') }}<br><span class="text-slate-400 text-sm">{{ substr($cita->hora,0,5) }}</span></td>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <td class="px-8 py-5">{{ $cita->usuario?->name ?? '—' }}</td>
                            @endif
                            <td class="px-8 py-5 text-sm text-slate-600">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</td>
                            <td class="px-8 py-5 font-black text-indigo-600">${{ number_format($cita->total, 0) }}</td>
                            <td class="px-8 py-5"><span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-slate-100">{{ $cita->estado }}</span></td>
                            <td class="px-8 py-5 text-right space-x-2">
                                <a href="{{ route('citas.show', $cita) }}" class="text-indigo-600 font-bold text-xs">Ver</a>
                                @if($cita->estado !== 'cancelada')
                                <a href="{{ route('citas.edit', $cita) }}" class="text-slate-600 font-bold text-xs">Editar</a>
                                @endif
                                @if($cita->puedeResenar() && $cita->user_id === auth()->id())
                                <a href="{{ route('resenas.create', $cita) }}" class="text-amber-600 font-bold text-xs">Reseñar</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-8 py-12 text-center text-slate-400 font-medium">No hay citas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-6">{{ $citas->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
