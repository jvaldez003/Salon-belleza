<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="font-black text-3xl text-slate-900 leading-tight">
                    {{ __('Control de Usuarios') }}
                </h1>
                <p class="text-slate-500 text-sm mt-1">Gestiona el equipo y sus niveles de acceso al sistema.</p>
            </div>
            @admin
            <a href="{{ route('usuario.create') }}" class="w-full md:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-8 rounded-2xl transition shadow-lg flex items-center justify-center group">
                <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                Nuevo Usuario
            </a>
            @endadmin
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl shadow-sm flex items-center animate-fade-in" role="alert">
                    <svg class="w-6 h-6 mr-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Usuarios</h4>
                    <p class="text-3xl font-black text-slate-900">{{ $users->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Administradores</h4>
                    <p class="text-3xl font-black text-indigo-600">{{ $users->where('role', 'admin')->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Editores</h4>
                    <p class="text-3xl font-black text-emerald-500">{{ $users->where('role', 'editor')->count() }}</p>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Usuario</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Correo Electrónico</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Rol / Acceso</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($users as $user)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-lg shadow-inner group-hover:scale-110 transition-transform">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-slate-900 font-bold">{{ $user->name }}</p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID: #{{ $user->id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-slate-500 font-medium">{{ $user->email }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($user->role == 'admin')
                                            <span class="inline-flex items-center px-4 py-1 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest border border-red-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2 animate-pulse"></span>
                                                Administrador
                                            </span>
                                        @elseif($user->role == 'editor')
                                            <span class="inline-flex items-center px-4 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2"></span>
                                                Editor
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest border border-slate-200">
                                                Usuario
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex justify-end space-x-2" x-data="{ open: false }">
                                            <a href="{{ route('usuario.show', $user->id) }}" class="p-3 bg-slate-50 text-slate-400 hover:text-slate-900 rounded-xl transition-all" title="Ver Detalle">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </a>
                                            
                                            @editor
                                                <a href="{{ route('usuario.edit', $user->id) }}" class="p-3 bg-indigo-50 text-indigo-400 hover:bg-indigo-600 hover:text-white rounded-xl transition-all" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </a>
                                            @endeditor
                                            
                                            @admin
                                                <button @click="open = true" class="p-3 bg-red-50 text-red-400 hover:bg-red-600 hover:text-white rounded-xl transition-all" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>

                                                <!-- Modal Deletion -->
                                                <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition>
                                                    <div class="bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl text-center" @click.away="open = false">
                                                        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                        </div>
                                                        <h3 class="text-2xl font-black text-slate-900 mb-2">¿Eliminar usuario?</h3>
                                                        <p class="text-slate-500 mb-8 text-sm leading-relaxed">Esta acción borrará a <b>{{ $user->name }}</b> permanentemente del sistema.</p>
                                                        <div class="flex flex-col space-y-3">
                                                            <form action="{{ route('usuario.destroy', $user->id) }}" method="POST">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-200 transition-all active:scale-95">Sí, eliminar ahora</button>
                                                            </form>
                                                            <button @click="open = false" class="w-full bg-slate-100 text-slate-600 font-bold py-4 rounded-2xl transition-all active:scale-95">Cancelar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endadmin
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>