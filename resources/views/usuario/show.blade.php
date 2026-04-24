<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Detalles del Usuario') }}
 </h2>
 </x-slot>
 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
 <div class="p-6 text-gray-900">
 <div class="mb-6">
 <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Usuario</h3>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block text-sm font-medium text-gray-700">Nombre:</label>
 <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700">Correo:</label>
 <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700">Rol:</label>
 <p class="mt-1 text-sm text-gray-900">{{ ucfirst($user->role) }}</p>
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700">Fecha de Registro:</label>
 <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</p>
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700">Última Actualización:</label>
 <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
 </div>
 </div>
 </div>
 <div class="flex items-center space-x-3">
 @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
 <a href="{{ route('usuario.edit', $user->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
 Editar
 </a>
 @endif
 <a href="{{ route('usuario.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
 Volver
 </a>
 </div>
 </div>
 </div>
 </div>
 </div>
</x-app-layout>