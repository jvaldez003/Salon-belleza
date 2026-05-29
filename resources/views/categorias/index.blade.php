<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-black text-3xl text-warm-900">Categorías de Servicios</h1>
                <p class="text-warm-400 text-sm mt-1">Organiza los servicios en categorías para facilitar la navegación.</p>
            </div>
            <a href="{{ route('servicios.index') }}" class="text-sm font-bold text-warm-500 hover:text-warm-900 transition">← Volver a Servicios</a>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
            <div class="alert-success">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Nueva categoría --}}
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-warm-200">
                <h2 class="font-black text-lg text-warm-900 mb-5">Nueva categoría</h2>
                <form method="POST" action="{{ route('categorias.store') }}" class="flex gap-3">
                    @csrf
                    <input type="text" name="nombre" placeholder="Ej: Cejas, Pestañas, Uñas…"
                           class="flex-1 bg-cream border-0 rounded-2xl py-3 px-5 font-bold text-warm-800 focus:ring-2 focus:ring-rose-primary"
                           required maxlength="80" value="{{ old('nombre') }}">
                    @error('nombre')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                    <button type="submit"
                            class="bg-rose-primary hover:bg-rose-dark text-white font-black px-6 py-3 rounded-2xl transition active:scale-95 shrink-0">
                        Agregar
                    </button>
                </form>
            </div>

            {{-- Lista de categorías --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-warm-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-warm-100 flex items-center justify-between">
                    <h2 class="font-black text-lg text-warm-900">Categorías existentes</h2>
                    <span class="text-xs font-black uppercase tracking-widest px-3 py-1 bg-rose-light/20 text-rose-deeper rounded-full">
                        {{ $categorias->count() }} total
                    </span>
                </div>

                @forelse($categorias as $cat)
                <div class="px-6 py-5 border-b border-warm-100 last:border-0 flex items-center gap-4 group hover:bg-cream transition"
                     x-data="{ editing: false }">

                    {{-- Imagen de categoría --}}
                    <div class="relative shrink-0 w-16 h-16 rounded-2xl overflow-hidden bg-rose-light/20 border border-warm-100">
                        @if($cat->imagen)
                            <img src="{{ asset('storage/'.$cat->imagen) }}" class="w-full h-full object-cover">
                            <form method="POST" action="{{ route('categorias.imagen.destroy', $cat) }}"
                                  class="absolute inset-0 opacity-0 group-hover:opacity-100 transition flex items-center justify-center bg-black/40">
                                @csrf @method('DELETE')
                                <button type="submit" title="Quitar imagen"
                                        class="w-7 h-7 bg-white text-red-500 rounded-full flex items-center justify-center text-xs font-black hover:bg-red-500 hover:text-white transition">✕</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('categorias.imagen.store', $cat) }}"
                                  enctype="multipart/form-data" id="img-form-{{ $cat->id }}">
                                @csrf
                                <label for="img-{{ $cat->id }}"
                                       class="w-16 h-16 flex flex-col items-center justify-center cursor-pointer text-rose-primary hover:text-rose-dark transition">
                                    <svg class="w-5 h-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span class="text-[9px] font-black uppercase tracking-widest leading-none">Foto</span>
                                </label>
                                <input type="file" id="img-{{ $cat->id }}" name="imagen" accept="image/*" class="hidden"
                                       onchange="document.getElementById('img-form-{{ $cat->id }}').submit()">
                            </form>
                        @endif
                    </div>

                    {{-- Inline edit nombre --}}
                    <form method="POST" action="{{ route('categorias.update', $cat) }}" class="flex-1 flex items-center gap-3">
                        @csrf @method('PUT')
                        <input type="text" name="nombre"
                               :readonly="!editing"
                               :class="editing ? 'bg-cream border-rose-primary ring-2 ring-rose-primary' : 'bg-transparent border-transparent'"
                               class="flex-1 border rounded-xl py-2 px-3 font-bold text-warm-800 focus:outline-none transition"
                               value="{{ $cat->nombre }}">
                        <button type="button" @click="editing = !editing"
                                class="text-xs font-black uppercase tracking-widest text-warm-400 hover:text-rose-primary transition"
                                x-text="editing ? 'Cancelar' : 'Editar'"></button>
                        <button type="submit" x-show="editing"
                                class="text-xs font-black uppercase tracking-widest text-rose-primary hover:text-rose-dark transition">
                            Guardar
                        </button>
                    </form>

                    {{-- Servicios count --}}
                    <span class="text-xs text-warm-400 font-bold shrink-0">
                        {{ $cat->servicios()->count() }} servicio(s)
                    </span>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('categorias.destroy', $cat) }}"
                          onsubmit="return confirm('¿Eliminar «{{ addslashes($cat->nombre) }}»? Los servicios quedarán sin categoría.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-8 h-8 rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition opacity-0 group-hover:opacity-100 active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                @empty
                <p class="px-8 py-10 text-center text-warm-400 text-sm font-bold">Aún no hay categorías. Crea la primera arriba.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
