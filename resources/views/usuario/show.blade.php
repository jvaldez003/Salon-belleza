@php
    $esYo = $user->is(auth()->user());
    $wa = $user->telefono ? preg_replace('/\D/', '', $user->telefono) : null;
    if ($wa && strlen($wa) === 10 && str_starts_with($wa, '3')) $wa = '57' . $wa;
@endphp

<x-app-layout :titulo="$user->name">
    <x-slot name="header">
        <div class="grid gap-3">
            <a href="{{ route('usuario.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-texto hover:text-oceano w-fit">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Usuarios
            </a>
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div class="grid gap-2">
                    <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">{{ $user->name }}</h1>
                    <p class="flex flex-wrap items-center gap-2 text-texto-suave">
                        <span @class(['badge', 'bg-oceano text-white' => $user->isAdmin(), 'bg-agua/35 text-abismo' => $user->isEditor(), 'bg-bruma-honda text-texto' => $user->isUsuario()])>{{ $user->rol_nombre }}</span>
                        @unless ($user->activo)<span class="badge bg-red-50 text-red-800">Desactivada</span>@endunless
                        @if ($esYo)<span class="text-sm">Esta es tu cuenta</span>@endif
                    </p>
                </div>
                <x-boton variante="secundario" :href="route('usuario.edit', $user->id)">Editar cuenta</x-boton>
            </div>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid lg:grid-cols-[20rem_minmax(0,1fr)] gap-6 items-start">
        <x-aviso class="lg:col-span-2" />

        <aside class="grid gap-6">
            <dl class="card p-5 grid gap-4">
                <div class="grid gap-0.5">
                    <dt class="text-sm text-texto-suave">Correo</dt>
                    <dd class="font-semibold text-tinta break-all"><a href="mailto:{{ $user->email }}" class="hover:text-oceano">{{ $user->email }}</a></dd>
                    @if (! $user->email_verified_at)<dd class="text-sm text-texto">Todavía no confirma su correo.</dd>@endif
                </div>
                <div class="grid gap-0.5">
                    <dt class="text-sm text-texto-suave">Celular</dt>
                    <dd class="font-semibold text-tinta tabular">
                        @if ($user->telefono)
                            {{ $user->telefono }}
                            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="ml-2 text-sm font-semibold text-oceano hover:underline">WhatsApp</a>
                        @else
                            <span class="font-normal text-texto-suave">Sin registrar</span>
                        @endif
                    </dd>
                </div>
                <div class="grid gap-0.5">
                    <dt class="text-sm text-texto-suave">Cuenta creada</dt>
                    <dd class="text-tinta">{{ $user->created_at?->translatedFormat('j \d\e F \d\e Y') }}</dd>
                </div>
                <div class="grid gap-0.5">
                    <dt class="text-sm text-texto-suave">Citas</dt>
                    <dd class="text-2xl font-semibold text-tinta tabular">{{ $user->citas_count }}</dd>
                </div>
            </dl>

            @unless ($esYo)
                <section class="card p-5 grid gap-3" aria-labelledby="t-acceso">
                    <h2 id="t-acceso" class="font-display text-lg">Acceso</h2>
                    <p class="text-sm text-texto-suave">
                        {{ $user->activo ? 'Puede entrar al sistema. Si la desactivas, no podrá entrar, pero se conservan sus datos y citas.' : 'No puede entrar al sistema. Sus datos y citas siguen guardados.' }}
                    </p>
                    <form method="POST" action="{{ route('usuario.toggleActivo', $user->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="{{ $user->activo ? 'btn-secondary' : 'btn-primary' }}">{{ $user->activo ? 'Desactivar cuenta' : 'Activar cuenta' }}</button>
                    </form>
                    <div class="pt-3 border-t border-linea">
                        <x-confirmar-borrado :action="route('usuario.destroy', $user->id)" :que="'la cuenta de ' . $user->name" texto="Eliminar" compacto />
                        <p class="mt-1 text-xs text-texto-suave">Borra la cuenta para siempre. Sus citas quedan sin cliente asignado.</p>
                    </div>
                </section>
            @endunless
        </aside>

        <section class="grid gap-3" aria-labelledby="t-citas">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <h2 id="t-citas" class="font-display text-xl">Últimas citas</h2>
                @if ($user->citas_count > $citas->count())
                    <span class="text-sm text-texto-suave">Mostrando {{ $citas->count() }} de {{ $user->citas_count }}</span>
                @endif
            </div>
            @if ($citas->isEmpty())
                <p class="card p-6 text-texto-suave">{{ $user->isUsuario() ? 'Todavía no ha reservado ninguna cita.' : 'Esta cuenta no tiene citas a su nombre.' }}</p>
            @else
                <ul class="card divide-y divide-linea overflow-hidden">
                    @foreach ($citas as $cita)
                        <li class="grid grid-cols-[minmax(0,1fr)_auto] gap-x-4 gap-y-1 items-center px-4 sm:px-5 py-3">
                            <div class="min-w-0">
                                <a href="{{ route('citas.show', $cita) }}" class="font-semibold text-tinta hover:text-oceano first-letter:uppercase block">{{ $cita->fecha_larga }}, {{ $cita->hora_texto }}</a>
                                <p class="text-sm text-texto-suave truncate">{{ $cita->servicios_texto }}</p>
                            </div>
                            <div class="grid justify-items-end gap-1">
                                <x-estado-cita :estado="$cita->estado" />
                                <x-precio :valor="$cita->total" class="!text-base" />
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-app-layout>
