{{-- Formulario de reseña: calificación con estrellas (radios accesibles) y comentario opcional. --}}
<x-app-layout titulo="Dejar reseña">
    <x-slot name="header">
        <div class="grid gap-1">
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">¿Cómo te fue?</h1>
            <p class="text-texto-suave first-letter:uppercase">{{ $cita->fecha_larga }}: {{ $cita->servicios_texto }}</p>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto px-4 sm:px-6 py-8 md:py-10">
        <form method="POST" action="{{ route('resenas.store', $cita) }}" class="card p-6 md:p-8 grid gap-6"
              x-data="{ nota: {{ (int) old('calificacion', 0) }}, sobre: 0 }">
            @csrf

            <fieldset class="grid gap-3">
                <legend class="label">Tu calificación</legend>
                <div class="flex gap-1" @mouseleave="sobre = 0">
                    @for ($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer" @mouseenter="sobre = {{ $i }}">
                            <input type="radio" name="calificacion" value="{{ $i }}" class="peer sr-only" x-model.number="nota" @checked(old('calificacion') == $i) required>
                            <span class="block text-[2.6rem] leading-none px-0.5 rounded-lg peer-focus-visible:ring-2 peer-focus-visible:ring-laguna transition-colors"
                                  :class="(sobre || nota) >= {{ $i }} ? 'text-laguna' : 'text-linea'" aria-hidden="true">★</span>
                            <span class="sr-only">{{ $i }} {{ $i === 1 ? 'estrella' : 'estrellas' }}</span>
                        </label>
                    @endfor
                </div>
                <p class="text-sm text-texto-suave h-5" x-text="['', 'No me gustó', 'Regular', 'Bien', 'Muy bien', 'Excelente'][sobre || nota]"></p>
                <x-input-error :messages="$errors->get('calificacion')" />
            </fieldset>

            <div class="grid gap-1.5">
                <label for="comentario" class="label">Comentario <span class="font-normal text-texto-suave">(opcional)</span></label>
                <textarea id="comentario" name="comentario" rows="4" maxlength="1000" class="form-input"
                          placeholder="Lo que más te gustó, o lo que podemos mejorar.">{{ old('comentario') }}</textarea>
                <x-input-error :messages="$errors->get('comentario')" />
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary">Enviar reseña</button>
                <a href="{{ route('resenas.historial') }}" class="btn text-texto hover:text-oceano">Cancelar</a>
            </div>
        </form>
    </div>
</x-app-layout>
