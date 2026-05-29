<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-warm-900">Reseñas de clientes</h1>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-8 rounded-3xl border border-warm-200 text-center">
                    <p class="text-[10px] font-black uppercase text-gold-dark">Promedio general</p>
                    <p class="text-4xl font-black text-gold-primary">{{ number_format($promedioGeneral ?? 0, 1) }} ★</p>
                </div>
                <div class="md:col-span-2 bg-white p-8 rounded-3xl border border-warm-200">
                    <h2 class="font-black text-lg text-warm-900 mb-4">Promedio por servicio</h2>
                    @forelse($promedioPorServicio as $item)
                    <div class="flex justify-between items-center py-2 border-b border-warm-100 last:border-0">
                        <span class="font-bold text-warm-800">{{ $item->nombre }}</span>
                        <span class="text-gold-primary font-black">{{ $item->promedio }} ★ <span class="text-warm-400 text-xs">({{ $item->total_resenas }} reseñas)</span></span>
                    </div>
                    @empty
                    <p class="text-warm-400 text-sm">Aún no hay reseñas por servicio.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-warm-200 overflow-hidden">
                <div class="px-8 py-6" style="background: var(--gradient-main);">
                    <h2 class="font-black text-white text-lg">Todas las reseñas</h2>
                </div>
                <div class="divide-y divide-warm-100">
                    @forelse($resenas as $resena)
                    <div class="p-8">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <p class="font-black text-warm-900">{{ $resena->usuario?->name }}</p>
                                <p class="text-sm text-warm-500">{{ $resena->cita?->fecha?->format('d/m/Y') }} · {{ $resena->cita?->servicios->pluck('nombre')->implode(', ') }}</p>
                                @if($resena->comentario)
                                    <p class="text-warm-600 mt-3 italic">"{{ $resena->comentario }}"</p>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-gold-primary text-xl">
                                    @for($i = 1; $i <= $resena->calificacion; $i++)★@endfor
                                </div>
                                <p class="text-[10px] text-warm-400 mt-1">{{ $resena->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="p-12 text-center text-warm-400">No hay reseñas registradas.</p>
                    @endforelse
                </div>
                <div class="p-6">{{ $resenas->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
