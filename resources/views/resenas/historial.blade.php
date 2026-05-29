<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-slate-900 italic">Historial y Reseñas</h1>
                <p class="text-slate-500 text-sm mt-1">Citas completadas y valoraciones del servicio.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl font-bold">{{ session('success') }}</div>
            @endif

            @if(auth()->user()->isAdmin())
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border flex gap-4 items-end">
                <div class="flex-1">
                    <label class="text-[10px] font-black uppercase text-slate-400">Buscar cliente</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Nombre del cliente">
                </div>
                <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase">Filtrar</button>
            </form>
            @endif

            <div class="space-y-4">
                @forelse($citas as $cita)
                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase text-indigo-500 tracking-widest mb-1">Cita #{{ $cita->id }}</p>
                            <h3 class="text-xl font-black text-slate-900">{{ $cita->fecha->format('d/m/Y') }} · {{ substr($cita->hora, 0, 5) }}</h3>
                            @if(auth()->user()->isAdmin())
                                <p class="text-sm text-slate-500 mt-1">Cliente: {{ $cita->usuario?->name }}</p>
                            @endif
                            <p class="text-sm text-slate-600 mt-2">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                            <p class="font-black text-indigo-600 mt-1">${{ number_format($cita->total, 0) }}</p>
                        </div>
                        <div class="text-right">
                            @if($cita->resena)
                                <div class="inline-flex items-center gap-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl {{ $i <= $cita->resena->calificacion ? 'text-amber-400' : 'text-slate-200' }}">★</span>
                                    @endfor
                                </div>
                                @if($cita->resena->comentario)
                                    <p class="text-sm text-slate-600 italic max-w-xs">"{{ $cita->resena->comentario }}"</p>
                                @endif
                                <p class="text-[10px] text-slate-400 mt-2">{{ $cita->resena->created_at->format('d/m/Y') }}</p>
                            @elseif($cita->user_id === auth()->id())
                                <a href="{{ route('resenas.create', $cita) }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-black px-6 py-3 rounded-2xl text-xs uppercase tracking-widest">
                                    Dejar reseña
                                </a>
                            @else
                                <span class="text-slate-400 text-sm">Sin reseña</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white p-12 rounded-3xl border text-center text-slate-400">
                    No hay citas completadas en el historial.
                    @if(!auth()->user()->isAdmin())
                        <p class="text-sm mt-2">Cuando finalice un servicio, podrás valorarlo aquí.</p>
                    @endif
                </div>
                @endforelse
            </div>
            <div class="mt-6">{{ $citas->links() }}</div>
        </div>
    </div>
</x-app-layout>
