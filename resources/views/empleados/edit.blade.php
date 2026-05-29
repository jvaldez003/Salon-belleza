<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('empleados.index') }}" class="p-2 bg-cream rounded-xl text-warm-400 hover:text-rose-dark transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="font-black text-2xl text-warm-900">Editar: {{ $empleado->nombre }}</h1>
        </div>
    </x-slot>

    <div class="py-12 bg-cream min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-warm-200">
                <form action="{{ route('empleados.update', $empleado) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="label">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $empleado->nombre) }}" required
                                   class="w-full bg-cream border-0 rounded-2xl py-4 px-5 font-bold text-warm-900 focus:ring-2 focus:ring-rose-primary">
                            @error('nombre')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="label">Especialidad</label>
                            <input type="text" name="especialidad" value="{{ old('especialidad', $empleado->especialidad) }}"
                                   class="w-full bg-cream border-0 rounded-2xl py-4 px-5 font-bold text-warm-900 focus:ring-2 focus:ring-rose-primary">
                            @error('especialidad')<p class="text-red-500 text-xs font-bold">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="label">Foto</label>
                        @if($empleado->foto)
                        <div class="flex items-center gap-4 mb-3">
                            <img src="{{ asset('storage/'.$empleado->foto) }}" class="w-16 h-16 rounded-2xl object-cover border border-warm-200">
                            <span class="text-sm text-warm-500">Foto actual. Sube una nueva para reemplazarla.</span>
                        </div>
                        @endif
                        <div class="relative group">
                            <input type="file" name="foto" accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="w-full bg-cream border-2 border-dashed border-warm-300 rounded-3xl py-8 flex flex-col items-center gap-2 group-hover:border-rose-primary transition-all">
                                <svg class="w-7 h-7 text-warm-300 group-hover:text-rose-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="text-sm font-bold text-warm-400">Cambiar foto</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="label">Servicios que puede realizar</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 p-4 bg-cream rounded-2xl max-h-56 overflow-y-auto">
                            @foreach($servicios as $srv)
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-warm-200 cursor-pointer hover:border-rose-primary transition-colors">
                                <input type="checkbox" name="servicios[]" value="{{ $srv->id }}"
                                       @checked(in_array($srv->id, old('servicios', $empleado->servicios->pluck('id')->toArray())))
                                       class="rounded text-rose-primary focus:ring-rose-primary border-warm-300">
                                <span class="text-sm font-bold text-warm-700">{{ $srv->nombre }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-3 py-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="activo" value="1" @checked(old('activo', $empleado->activo)) class="sr-only peer">
                            <div class="w-11 h-6 bg-warm-200 peer-checked:bg-rose-primary rounded-full transition-colors after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                        <span class="text-sm font-bold text-warm-700">Empleado activo</span>
                    </div>

                    <div class="border-t border-warm-200 pt-6">
                        @include('empleados._horario_form')
                    </div>

                    <div class="pt-4 border-t border-warm-100 flex gap-4">
                        <button type="submit" class="flex-1 bg-rose-primary hover:bg-rose-dark text-white font-black py-4 rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            Guardar Cambios
                        </button>
                        <a href="{{ route('empleados.index') }}" class="flex-1 bg-cream hover:bg-warm-200 text-warm-600 text-center font-black py-4 rounded-2xl transition-all">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
