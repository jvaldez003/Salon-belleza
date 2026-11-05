{{-- Cuentas del sistema: equipo y clientes. Solo administradores. --}}
@php
    $roles = \App\Models\User::ROLES;
    $filtrando = request()->filled('buscar') || request()->filled('rol') || request()->filled('activo');
@endphp

<x-app-layout titulo="Usuarios">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Usuarios</h1>
                <p class="text-texto-suave">
                    {{ $totales->get('usuario', 0) }} {{ $totales->get('usuario', 0) == 1 ? 'cliente' : 'clientes' }},
                    {{ $totales->get('editor', 0) }} en recepción y
                    {{ $totales->get('admin', 0) }} {{ $totales->get('admin', 0) == 1 ? 'administrador' : 'administradores' }}.
                </p>
            </div>
            <x-boton :href="route('usuario.create')">Nueva cuenta</x-boton>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <x-aviso />

        <form method="GET" class="flex flex-wrap items-end gap-3" role="search">
            <div class="grid gap-1.5 grow basis-64 max-w-md">
                <label for="buscar" class="label !mb-0">Buscar</label>
                <input id="buscar" type="search" name="buscar" value="{{ request('buscar') }}" class="form-input" placeholder="Nombre, correo o celular">
            </div>
            <div class="grid gap-1.5">
                <label for="rol" class="label !mb-0">Rol</label>
                <select id="rol" name="rol" class="form-input !w-auto pr-10" onchange="this.form.requestSubmit()">
                    <option value="">Todos</option>
                    @foreach ($roles as $clave => $r)
                        <option value="{{ $clave }}" @selected(request('rol') === $clave)>{{ $r['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid gap-1.5">
                <label for="activo" class="label !mb-0">Acceso</label>
                <select id="activo" name="activo" class="form-input !w-auto pr-10" onchange="this.form.requestSubmit()">
                    <option value="">Todos</option>
                    <option value="1" @selected(request('activo') === '1')>Pueden entrar</option>
                    <option value="0" @selected(request('activo') === '0')>Desactivadas</option>
                </select>
            </div>
            <button type="submit" class="btn-secondary">Buscar</button>
            @if ($filtrando)
                <a href="{{ route('usuario.index') }}" class="btn text-texto hover:text-oceano">Quitar filtros</a>
            @endif
        </form>

        @if ($users->isEmpty())
            <p class="card p-6 text-texto-suave">{{ $filtrando ? 'Ninguna cuenta coincide con la búsqueda.' : 'Todavía no hay cuentas.' }}</p>
        @else
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <caption class="sr-only">Cuentas del sistema</caption>
                        <thead class="bg-bruma text-sm text-texto">
                            <tr>
                                <th scope="col" class="px-4 sm:px-5 py-3 font-semibold">Nombre</th>
                                <th scope="col" class="px-4 py-3 font-semibold hidden md:table-cell">Celular</th>
                                <th scope="col" class="px-4 py-3 font-semibold hidden sm:table-cell">Rol</th>
                                <th scope="col" class="px-4 py-3 font-semibold text-right hidden sm:table-cell">Citas</th>
                                <th scope="col" class="px-4 sm:px-5 py-3"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-linea">
                            @foreach ($users as $u)
                                <tr @class(['bg-bruma/60' => ! $u->activo])>
                                    <td class="px-4 sm:px-5 py-3 max-w-0 w-full">
                                        <a href="{{ route('usuario.show', $u->id) }}" class="block font-semibold text-tinta hover:text-oceano truncate">
                                            {{ $u->name }}@if ($u->is(auth()->user()))<span class="font-normal text-texto-suave"> (tú)</span>@endif
                                        </a>
                                        <span class="block text-sm text-texto-suave truncate">{{ $u->email }}</span>
                                        <span class="sm:hidden mt-1 flex flex-wrap items-center gap-2 text-xs">
                                            <span @class(['badge', 'bg-oceano text-white' => $u->isAdmin(), 'bg-agua/35 text-abismo' => $u->isEditor(), 'bg-bruma-honda text-texto' => $u->isUsuario()])>{{ $u->rol_nombre }}</span>
                                            @unless ($u->activo)<span class="font-semibold text-red-800">Desactivada</span>@endunless
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-texto tabular whitespace-nowrap hidden md:table-cell">{{ $u->telefono ?: '—' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap hidden sm:table-cell">
                                        <span @class(['badge', 'bg-oceano text-white' => $u->isAdmin(), 'bg-agua/35 text-abismo' => $u->isEditor(), 'bg-bruma-honda text-texto' => $u->isUsuario()])>{{ $u->rol_nombre }}</span>
                                        @unless ($u->activo)<span class="block mt-1 text-xs font-semibold text-red-800">Desactivada</span>@endunless
                                    </td>
                                    <td class="px-4 py-3 text-right tabular hidden sm:table-cell {{ $u->citas_count ? 'font-semibold text-tinta' : 'text-texto-suave' }}">{{ $u->citas_count ?: '—' }}</td>
                                    <td class="px-4 sm:px-5 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('usuario.edit', $u->id) }}" class="text-sm font-semibold text-oceano hover:underline underline-offset-4">Editar<span class="sr-only"> la cuenta de {{ $u->name }}</span></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
