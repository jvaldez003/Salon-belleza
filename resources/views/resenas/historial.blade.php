<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-warm-900">Historial y Reseñas</h1>
                <p class="text-warm-400 text-sm mt-1">Citas completadas y valoraciones del servicio.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 alert-success font-bold">{{ session('success') }}</div>
            @endif

            @if(auth()->user()->isAdmin())
            <form method="GET" class="mb-8 bg-white p-6 rounded-3xl border border-warm-200 flex gap-4 items-end">
                <div class="flex-1">
                    <label class="label">Buscar cliente</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="mt-1 w-full rounded-xl border-warm-200 bg-cream focus:ring-rose-primary" placeholder="Nombre del cliente">
                </div>
                <button type="submit" class="bg-warm-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase hover:bg-warm-800 transition">Filtrar</button>
            </form>
            @endif

            <div class="space-y-4">
                @forelse($citas as $cita)
                <div class="bg-white p-8 rounded-3xl border border-warm-200 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase text-rose-dark tracking-widest mb-1">Cita #{{ $cita->id }}</p>
                            <h3 class="text-xl font-black text-warm-900">{{ $cita->fecha->format('d/m/Y') }} · {{ substr($cita->hora, 0, 5) }}</h3>
                            @if(auth()->user()->isAdmin())
                                <p class="text-sm text-warm-500 mt-1">Cliente: {{ $cita->usuario?->name }}</p>
                            @endif
                            <p class="text-sm text-warm-600 mt-2">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
                            <p class="font-black text-rose-dark mt-1">${{ number_format($cita->total, 0) }}</p>
                        </div>
                        <div class="text-right">
                            @if($cita->resena)
                                <div class="inline-flex items-center gap-1 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="text-xl {{ $i <= $cita->resena->calificacion ? 'text-gold-primary' : 'text-warm-200' }}">★</span>
                                    @endfor
                                </div>
                                @if($cita->resena->comentario)
                                    <p class="text-sm text-warm-600 italic max-w-xs">"{{ $cita->resena->comentario }}"</p>
                                @endif
                                <p class="text-[10px] text-warm-400 mt-2">{{ $cita->resena->created_at->format('d/m/Y') }}</p>
                            @elseif($cita->user_id === auth()->id())
                                <a href="{{ route('resenas.create', $cita) }}" class="inline-block bg-gold-primary hover:bg-gold-dark text-white font-black px-6 py-3 rounded-2xl text-xs uppercase tracking-widest transition">
                                    Dejar reseña
                                </a>
                            @else
                                <span class="text-warm-400 text-sm">Sin reseña</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white p-12 rounded-3xl border border-warm-200 text-center text-warm-400">
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
