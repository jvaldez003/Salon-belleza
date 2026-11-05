<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-cream">
                <th class="px-8 py-4 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Servicio</th>
                <th class="px-8 py-4 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Precio</th>
                <th class="px-8 py-4 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em]">Duración</th>
                @admin
                <th class="px-8 py-4 text-[10px] font-black text-warm-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                @endadmin
            </tr>
        </thead>
        <tbody class="divide-y divide-warm-100">
            @foreach($items as $servicio)
            <tr class="group hover:bg-cream/60 transition-colors">

                {{-- Servicio --}}
                <td class="px-8 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden shrink-0 bg-warm-100 group-hover:scale-105 transition-transform duration-300">
                            @if($servicio->imagenes->isNotEmpty())
                                <img src="{{ asset('storage/'.$servicio->imagenes->first()->url) }}"
                                     class="w-full h-full object-cover">
                            @elseif($servicio->categoria?->imagenes->isNotEmpty())
                                <img src="{{ asset('storage/'.$servicio->categoria->imagenes->first()->url) }}"
                                     class="w-full h-full object-cover opacity-60">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-lg font-black text-warm-300">
                                        {{ mb_strtoupper(mb_substr($servicio->nombre, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-warm-900 leading-snug">{{ $servicio->nombre }}</p>
                            @if($servicio->descripcion)
                            <p class="text-xs text-warm-400 mt-0.5 line-clamp-1 max-w-xs">{{ $servicio->descripcion }}</p>
                            @endif
                        </div>
                    </div>
                </td>

                {{-- Precio --}}
                <td class="px-8 py-5">
                    <span class="text-xl font-black text-rose-dark">${{ number_format($servicio->precio, 0) }}</span>
                </td>

                {{-- Duración --}}
                <td class="px-8 py-5">
                    @if($servicio->duracion)
                    <span class="text-sm font-semibold text-warm-500">{{ $servicio->duracion }} min</span>
                    @else
                    <span class="text-warm-300 text-sm">—</span>
                    @endif
                </td>

                {{-- Acciones --}}
                @admin
                <td class="px-8 py-5 text-right">
                    <div class="flex justify-end gap-2" x-data="{ openDelete: false }">
                        <a href="{{ route('servicios.edit', $servicio->id) }}"
                           class="p-2.5 bg-cream text-warm-500 hover:bg-rose-primary hover:text-white rounded-xl transition-all"
                           title="Editar">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </a>
                        <button @click="openDelete = true"
                                class="p-2.5 bg-red-50 text-red-400 hover:bg-red-500 hover:text-white rounded-xl transition-all"
                                title="Eliminar">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>

                        {{-- Modal confirmación --}}
                        <div x-show="openDelete" x-cloak
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-warm-900/60 backdrop-blur-sm"
                             x-transition>
                            <div class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center"
                                 @click.away="openDelete = false">
                                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-5">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                </div>
                                <h3 class="text-xl font-black text-warm-900 mb-2">¿Eliminar servicio?</h3>
                                <p class="text-warm-500 mb-7 text-sm leading-relaxed">
                                    Borrarás <b>{{ $servicio->nombre }}</b> del catálogo de forma permanente.
                                </p>
                                <div class="flex flex-col gap-3">
                                    <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3.5 rounded-2xl transition active:scale-95">
                                            Sí, eliminar
                                        </button>
                                    </form>
                                    <button @click="openDelete = false"
                                            class="w-full bg-cream text-warm-600 font-bold py-3.5 rounded-2xl transition active:scale-95">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                @endadmin

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
