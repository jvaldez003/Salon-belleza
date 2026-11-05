<x-app-layout titulo="Horario del salón">
    <x-slot name="header">
        <div class="grid gap-1">
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Horario del salón</h1>
            <p class="text-texto-suave max-w-prose">Los clientes solo pueden reservar los días y horas en que el salón está abierto.</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        <form method="POST" action="{{ route('horarios.update') }}" class="grid gap-8">
            @csrf
            @method('PUT')

            <x-horario-semana :dias="$horarios" si="Abierto" no="Cerrado">
                <h2 class="font-display text-xl">Días de atención</h2>
            </x-horario-semana>

            @php $intervalo = (int) old('intervalo', config('salon.intervalo_minutos', 30)); @endphp
            <section class="card p-5 grid gap-3" aria-labelledby="t-intervalo">
                <div class="grid gap-1">
                    <h2 id="t-intervalo" class="font-display text-xl">Horas que se ofrecen al reservar</h2>
                    <p class="text-sm text-texto-suave">Cada cuánto aparece una hora para elegir. Con 30 minutos, el cliente ve 9:00, 9:30, 10:00…</p>
                </div>
                <fieldset class="flex flex-wrap gap-2">
                    <legend class="sr-only">Intervalo entre horas</legend>
                    @foreach ([15, 30, 45, 60] as $m)
                        <label class="cursor-pointer">
                            <input type="radio" name="intervalo" value="{{ $m }}" @checked($intervalo === $m) class="peer sr-only">
                            <span class="inline-flex items-center min-h-[42px] px-4 rounded-full border border-linea bg-white text-sm font-semibold text-texto
                                         peer-checked:bg-oceano peer-checked:border-oceano peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-laguna peer-focus-visible:ring-offset-2">
                                {{ $m === 60 ? 'Cada hora' : "Cada $m min" }}
                            </span>
                        </label>
                    @endforeach
                </fieldset>
                <x-input-error :messages="$errors->get('intervalo')" />
            </section>

            <p class="text-sm text-texto-suave">Cada empleado tiene además su propio horario. <a href="{{ route('empleados.index') }}" class="font-semibold text-oceano hover:underline">Ver el equipo</a></p>

            <div class="sticky bottom-0 -mx-4 sm:mx-0 px-4 sm:px-0 py-4 bg-superficie/95 backdrop-blur border-t border-linea flex items-center gap-3">
                <button type="submit" class="btn-primary">Guardar horario</button>
            </div>
        </form>
    </div>
</x-app-layout>
