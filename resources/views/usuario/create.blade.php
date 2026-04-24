<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('usuario.index') }}" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="font-black text-2xl text-slate-900 leading-tight">
                {{ __('Nuevo Miembro') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                
                @if ($errors->any())
                    <div class="mb-8 bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-2xl">
                        <ul class="list-disc list-inside text-sm font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('usuario.store') }}" class="space-y-8">
                    @csrf

                    <div class="space-y-2">
                        <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nombre Completo</label>
                        <input type="text" name="name" id="name" placeholder="Ej: Juan Pérez"
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800"
                               value="{{ old('name') }}" required>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                        <input type="email" name="email" id="email" placeholder="ejemplo@correo.com"
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800"
                               value="{{ old('email') }}" required>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contraseña Temporal</label>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800"
                               required>
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Rol de Acceso</label>
                        <select name="role" id="role"
                                class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800 appearance-none cursor-pointer"
                                required>
                            <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>Usuario (Básico)</option>
                            <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor (Gestión)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador (Total)</option>
                        </select>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-black py-4 rounded-2xl shadow-lg transition-all transform active:scale-95 flex items-center justify-center">
                            Crear Cuenta
                        </button>
                        <a href="{{ route('usuario.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center font-black py-4 rounded-2xl transition-all">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>