<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('protocolos.index') }}" class="p-2 bg-white rounded-xl border border-warm-200 text-warm-400 hover:text-warm-900 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="font-black text-3xl text-warm-900">Nuevo Protocolo</h1>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-warm-200">
                <form method="POST" action="{{ route('protocolos.store') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="label">Nombre del protocolo</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" required maxlength="120"
                               placeholder="Ej: Lifting de Pestañas, Microblading…"
                               class="w-full bg-cream border-0 rounded-2xl py-4 px-5 font-bold text-warm-800 focus:ring-2 focus:ring-rose-primary">
                        @error('nombre')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="label">Descripción</label>
                        <textarea name="descripcion" rows="5" maxlength="2000"
                                  placeholder="Describe en qué consiste el protocolo, beneficios, duración…"
                                  class="w-full bg-cream border-0 rounded-2xl py-4 px-5 text-sm text-warm-700 leading-relaxed focus:ring-2 focus:ring-rose-primary">{{ old('descripcion') }}</textarea>
                        @error('descripcion')<p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    <p class="text-xs text-warm-400 font-bold bg-cream rounded-2xl px-5 py-3">
                        💡 Después de crear el protocolo podrás agregar imágenes y videos.
                    </p>

                    <div class="flex gap-4 pt-2">
                        <button type="submit"
                                class="flex-1 bg-rose-primary hover:bg-rose-dark text-white font-black py-4 rounded-2xl transition active:scale-95">
                            Crear y agregar medios →
                        </button>
                        <a href="{{ route('protocolos.index') }}"
                           class="flex-1 bg-cream hover:bg-warm-100 text-warm-600 text-center font-black py-4 rounded-2xl transition">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
