<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 italic">Reseñas de clientes</h1>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-8 rounded-3xl border text-center">
                    <p class="text-[10px] font-black uppercase text-amber-500">Promedio general</p>
                    <p class="text-4xl font-black text-amber-500">{{ number_format($promedioGeneral ?? 0, 1) }} ★</p>
                </div>
                <div class="md:col-span-2 bg-white p-8 rounded-3xl border">
                    <h2 class="font-black text-lg mb-4">Promedio por servicio</h2>
                    @forelse($promedioPorServicio as $item)
                    <div class="flex justify-between items-center py-2 border-b border-slate-50 last:border-0">
                        <span class="font-bold text-slate-800">{{ $item->nombre }}</span>
                        <span class="text-amber-500 font-black">{{ $item->promedio }} ★ <span class="text-slate-400 text-xs">({{ $item->total_resenas }} reseñas)</span></span>
                    </div>
                    @empty
                    <p class="text-slate-400 text-sm">Aún no hay reseñas por servicio.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border overflow-hidden">
                <div class="px-8 py-6 bg-slate-900">
                    <h2 class="font-black text-white text-lg">Todas las reseñas</h2>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($resenas as $resena)
                    <div class="p-8">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <p class="font-black text-slate-900">{{ $resena->usuario?->name }}</p>
                                <p class="text-sm text-slate-500">{{ $resena->cita?->fecha?->format('d/m/Y') }} · {{ $resena->cita?->servicios->pluck('nombre')->implode(', ') }}</p>
                                @if($resena->comentario)
                                    <p class="text-slate-600 mt-3 italic">"{{ $resena->comentario }}"</p>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-amber-400 text-xl">
                                    @for($i = 1; $i <= $resena->calificacion; $i++)★@endfor
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $resena->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="p-12 text-center text-slate-400">No hay reseñas registradas.</p>
                    @endforelse
                </div>
                <div class="p-6">{{ $resenas->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
