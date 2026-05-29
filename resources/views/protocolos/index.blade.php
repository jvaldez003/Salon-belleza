<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-black text-3xl text-warm-900">Protocolos de Tratamiento</h1>
                <p class="text-warm-400 text-sm mt-1">Gestiona los protocolos que se muestran en la página principal.</p>
            </div>
            <a href="{{ route('protocolos.create') }}"
               class="bg-rose-primary hover:bg-rose-dark text-white font-black px-8 py-3 rounded-2xl transition shadow-sm text-sm active:scale-95">
                + Nuevo Protocolo
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
            <div class="alert-success mb-6">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-warm-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-warm-100">
                    <p class="text-xs font-black uppercase tracking-widest text-warm-400">{{ $protocolos->count() }} protocolo(s)</p>
                </div>

                @forelse($protocolos as $protocolo)
                <div class="px-8 py-6 border-b border-warm-100 last:border-0 flex items-center gap-6 group hover:bg-cream transition">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-1">
                            <p class="font-black text-warm-900 truncate">{{ $protocolo->nombre }}</p>
                            @if(!$protocolo->activo)
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 bg-warm-100 text-warm-400 rounded-full">Oculto</span>
                            @endif
                        </div>
                        @if($protocolo->descripcion)
                        <p class="text-sm text-warm-500 line-clamp-1">{{ $protocolo->descripcion }}</p>
                        @endif
                    </div>
                    <span class="text-xs text-warm-400 font-bold shrink-0">{{ $protocolo->medios_count }} medio(s)</span>
                    <a href="{{ route('protocolos.edit', $protocolo) }}"
                       class="text-xs font-black uppercase tracking-widest text-rose-primary hover:text-rose-dark transition shrink-0">
                        Editar
                    </a>
                    <form method="POST" action="{{ route('protocolos.destroy', $protocolo) }}"
                          onsubmit="return confirm('¿Eliminar este protocolo y todos sus medios?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-8 h-8 rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition opacity-0 group-hover:opacity-100 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                @empty
                <div class="px-8 py-16 text-center">
                    <p class="text-warm-400 font-bold mb-4">Aún no hay protocolos.</p>
                    <a href="{{ route('protocolos.create') }}" class="btn-primary">Crear primer protocolo</a>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
