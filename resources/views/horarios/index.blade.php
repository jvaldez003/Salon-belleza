<x-app-layout>
    <x-slot name="header">
        <h1 class="font-black text-3xl text-warm-900">Horarios del Salón</h1>
        <p class="text-warm-400 text-sm mt-1">Define los días y horas de atención. Los clientes solo podrán agendar en estos horarios.</p>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 alert-success">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('horarios.update') }}">
                @csrf @method('PUT')

                <div class="space-y-3">
                    @foreach($horarios as $h)
                    <div class="bg-white rounded-2xl border border-warm-200 shadow-sm px-6 py-4"
                         x-data="{ abierto: {{ $h->activo ? 'true' : 'false' }} }">
                        <div class="flex items-center justify-between gap-4">
                            <span class="font-black text-warm-800 w-28">{{ $h->nombre }}</span>

                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="horarios[{{ $h->dia }}][activo]" value="1"
                                       x-model="abierto" class="sr-only peer">
                                <div class="w-11 h-6 bg-warm-200 peer-checked:bg-rose-primary rounded-full transition-colors peer-focus:ring-2 peer-focus:ring-rose-light after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>

                            <div class="flex items-center gap-2 flex-1" :class="abierto ? '' : 'opacity-40 pointer-events-none'">
                                <input type="hidden" name="horarios[{{ $h->dia }}][dia]" value="{{ $h->dia }}">
                                <input type="time" name="horarios[{{ $h->dia }}][hora_apertura]"
                                       value="{{ $h->hora_apertura }}"
                                       class="flex-1 rounded-xl border-warm-200 bg-cream text-sm font-bold text-warm-700 focus:ring-rose-primary focus:border-rose-primary"
                                       :disabled="!abierto">
                                <span class="text-warm-400 font-bold text-xs">–</span>
                                <input type="time" name="horarios[{{ $h->dia }}][hora_cierre]"
                                       value="{{ $h->hora_cierre }}"
                                       class="flex-1 rounded-xl border-warm-200 bg-cream text-sm font-bold text-warm-700 focus:ring-rose-primary focus:border-rose-primary"
                                       :disabled="!abierto">
                            </div>

                            <span x-show="abierto" class="text-xs font-black text-rose-dark w-16 text-right">Abierto</span>
                            <span x-show="!abierto" class="text-xs font-black text-warm-400 w-16 text-right">Cerrado</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-6 bg-white rounded-2xl border border-warm-200 shadow-sm px-6 py-4">
                    <label class="label">Intervalo entre citas (minutos)</label>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="number" name="intervalo" min="15" max="120" step="15"
                               value="{{ config('salon.intervalo_minutos', 30) }}"
                               class="w-28 rounded-xl border-warm-200 bg-cream font-bold text-warm-800 focus:ring-rose-primary focus:border-rose-primary">
                        <span class="text-sm text-warm-400">Ej: 30 genera slots cada media hora</span>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-rose-primary hover:bg-rose-dark text-white font-black py-4 rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Guardar Horarios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
