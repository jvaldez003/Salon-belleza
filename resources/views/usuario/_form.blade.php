{{--
    Campos de la cuenta (crear y editar). Variables: $user (o null)
--}}
@php
    $u = $user ?? null;
    $rolActual = old('role', $u?->role ?? 'usuario');
    $esYo = $u && $u->is(auth()->user());
@endphp

<section class="grid gap-6" aria-labelledby="t-datos">
    <h2 id="t-datos" class="font-display text-xl">Datos</h2>
    <div class="grid gap-1.5">
        <label for="name" class="label">Nombre completo</label>
        <input id="name" type="text" name="name" value="{{ old('name', $u?->name) }}" required maxlength="255" autocomplete="off" class="form-input">
        <x-input-error :messages="$errors->get('name')" />
    </div>
    <div class="grid sm:grid-cols-[minmax(0,1fr)_14rem] gap-6">
        <div class="grid gap-1.5 content-start">
            <label for="email" class="label">Correo</label>
            <input id="email" type="email" name="email" value="{{ old('email', $u?->email) }}" required autocomplete="off" class="form-input">
            <p class="text-xs text-texto-suave">Con este correo entra al sistema.</p>
            <x-input-error :messages="$errors->get('email')" />
        </div>
        <div class="grid gap-1.5 content-start">
            <label for="telefono" class="label">Celular <span class="font-normal text-texto-suave">(opcional)</span></label>
            <input id="telefono" type="tel" name="telefono" value="{{ old('telefono', $u?->telefono) }}" maxlength="15" inputmode="tel" autocomplete="off" class="form-input tabular" placeholder="3001234567">
            <x-input-error :messages="$errors->get('telefono')" />
        </div>
    </div>
</section>

<fieldset class="grid gap-3 pt-8 border-t border-linea">
    <legend class="font-display text-xl text-tinta mb-1">Qué puede hacer</legend>
    @foreach (\App\Models\User::ROLES as $clave => $rol)
        <label @class(['flex items-start gap-3 rounded-xl border border-linea bg-white px-4 py-3 cursor-pointer has-[:checked]:border-oceano has-[:checked]:bg-bruma',
                       'opacity-60 cursor-not-allowed' => $esYo && $clave !== 'admin'])>
            <input type="radio" name="role" value="{{ $clave }}" @checked($rolActual === $clave) @disabled($esYo && $clave !== 'admin')
                   class="mt-1 w-4 h-4 border-linea text-oceano focus:ring-laguna">
            <span>
                <span class="block font-semibold text-tinta">{{ $rol['nombre'] }}</span>
                <span class="block text-sm text-texto-suave">{{ $rol['detalle'] }}</span>
            </span>
        </label>
    @endforeach
    @if ($esYo)
        <p class="text-sm text-texto-suave">Es tu cuenta: no puedes quitarte el rol de administrador.</p>
    @endif
    <x-input-error :messages="$errors->get('role')" />
</fieldset>

<section class="grid gap-4 pt-8 border-t border-linea" aria-labelledby="t-clave" x-data="{ ver: false }">
    <div class="grid gap-1">
        <h2 id="t-clave" class="font-display text-xl">{{ $u ? 'Cambiar contraseña' : 'Contraseña' }}</h2>
        <p class="text-sm text-texto-suave">{{ $u ? 'Déjala en blanco para no cambiarla.' : 'Mínimo 8 caracteres. Compártela con la persona por un medio privado.' }}</p>
    </div>
    <div class="grid sm:grid-cols-2 gap-6">
        <div class="grid gap-1.5 content-start">
            <label for="password" class="label">{{ $u ? 'Nueva contraseña' : 'Contraseña' }}</label>
            <input id="password" :type="ver ? 'text' : 'password'" type="password" name="password" @unless ($u) required @endunless minlength="8" autocomplete="new-password" class="form-input">
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <div class="grid gap-1.5 content-start">
            <label for="password_confirmation" class="label">Repite la contraseña</label>
            <input id="password_confirmation" :type="ver ? 'text' : 'password'" type="password" name="password_confirmation" @unless ($u) required @endunless minlength="8" autocomplete="new-password" class="form-input">
        </div>
    </div>
    <label class="inline-flex items-center gap-2 text-sm text-texto cursor-pointer w-fit">
        <input type="checkbox" x-model="ver" class="w-4 h-4 rounded border-linea text-oceano focus:ring-laguna">
        Mostrar contraseña
    </label>
</section>
