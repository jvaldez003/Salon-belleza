<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Crear Usuario') }}
 </h2>
 </x-slot>
 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
 <div class="p-6 text-gray-900">
 @if ($errors->any())
 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
 <ul>
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif
 <form method="POST" action="{{ route('usuario.store') }}">
 @csrf

 <div class="mb-4">
 <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
 <input type="text" name="name" id="name"
 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 value="{{ old('name') }}" required>
 </div>
 <div class="mb-4">
 <label for="email" class="block text-sm font-medium text-gray-700">Correo</label>
 <input type="email" name="email" id="email"
 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 value="{{ old('email') }}" required>
 </div>
 <div class="mb-4">
 <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
 <input type="password" name="password" id="password"
 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 required>
 </div>
 <div class="mb-4">
 <label for="role" class="block text-sm font-medium text-gray-700">Rol</label>
 <select name="role" id="role"
 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 required>
 <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>Usuario</option>
 <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor</option>
 <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
 </select>
 </div>
 <div class="flex items-center justify-between">
 <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
 Guardar
 </button>
 <a href="{{ route('usuario.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
 Cancelar
 </a>
 </div>
 </form>
 </div>
 </div>
 </div>
 </div>
</x-app-layout>