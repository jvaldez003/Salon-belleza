<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-slate-900 italic">Valorar servicio</h1>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm mb-6">
                <p class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Cita del {{ $cita->fecha->format('d/m/Y') }}</p>
                <p class="font-bold text-slate-800 mt-1">{{ $cita->servicios->pluck('nombre')->implode(', ') }}</p>
            </div>

            <form method="POST" action="{{ route('resenas.store', $cita) }}" class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-sm space-y-6">
                @csrf

                <div>
                    <x-input-label for="calificacion" value="Calificación (1 a 5 estrellas)" />
                    <select name="calificacion" id="calificacion" required class="mt-1 w-full rounded-xl border-slate-200 font-bold">
                        <option value="">Selecciona...</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" @selected(old('calificacion') == $i)>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('calificacion')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="comentario" value="Comentario (opcional)" />
                    <textarea name="comentario" id="comentario" rows="4" maxlength="1000"
                        class="mt-1 w-full rounded-xl border-slate-200"
                        placeholder="Cuéntanos cómo fue tu experiencia...">{{ old('comentario') }}</textarea>
                    <x-input-error :messages="$errors->get('comentario')" class="mt-2" />
                </div>

                <div class="flex gap-4">
                    <x-primary-button>Enviar reseña</x-primary-button>
                    <a href="{{ route('resenas.historial') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 rounded-xl font-bold text-slate-600 text-sm">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
