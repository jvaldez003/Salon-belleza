<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('usuario.index') }}" class="p-2 bg-white rounded-xl shadow-sm text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="font-black text-2xl text-slate-900 leading-tight">
                {{ __('Configurar Perfil') }}
            </h1>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                
                <div class="flex items-center space-x-4 mb-10 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black text-2xl shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-500 font-medium">Editando permisos y datos básicos</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('usuario.update', $user->id) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nombre Completo</label>
                        <input type="text" name="name" id="name"
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800"
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
                        <input type="email" name="email" id="email"
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800"
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" id="password" placeholder="Dejar en blanco para no cambiar"
                               class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Rol / Nivel de Acceso</label>
                        <select name="role" id="role"
                                class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800 appearance-none cursor-pointer"
                                required>
                            <option value="usuario" {{ old('role', $user->role) == 'usuario' ? 'selected' : '' }}>Usuario (Básico)</option>
                            <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor (Gestión)</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador (Total)</option>
                        </select>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex flex-col md:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all transform active:scale-95 flex items-center justify-center">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('usuario.index') }}" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-center font-black py-4 rounded-2xl transition-all">
                            Descartar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>