<x-app-layout>
 <x-slot name="header">
 <h2 class="font-semibold text-xl text-gray-800 leading-tight">
 {{ __('Panel de Administración') }}
 </h2>
 </x-slot>
 <div class="py-12">
 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
 <div class="p-6 text-gray-900">
 <h3 class="text-lg font-medium mb-4">Estadísticas del Sistema</h3>
 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
 <div class="p-4 bg-blue-100 border border-blue-200 rounded-lg">
 <h4 class="font-bold text-blue-800">Total Usuarios</h4>
 <p class="text-2xl text-blue-900">{{ $usersCount }}</p>
 </div>
 <div class="p-4 bg-green-100 border border-green-200 rounded-lg">
 <h4 class="font-bold text-green-800">Total Servicios</h4>
 <p class="text-2xl text-green-900">{{ $serviciosCount }}</p>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
</x-app-layout>