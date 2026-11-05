@php $abierto = $errors->userDeletion->isNotEmpty(); @endphp

<div class="grid gap-4" x-data="{ abierto: {{ $abierto ? 'true' : 'false' }} }">
    <header class="grid gap-1">
        <h2 class="font-display text-xl">Eliminar mi cuenta</h2>
        <p class="text-sm text-texto-suave max-w-prose">Se borran tu cuenta y tus datos para siempre, y se cierra la sesión. Tus citas pasadas quedan en el salón sin tu nombre.</p>
    </header>

    <div x-show="!abierto">
        <button type="button" @click="abierto = true; $nextTick(() => $refs.clave.focus())" class="font-semibold text-red-700 hover:underline underline-offset-4">Eliminar mi cuenta</button>
    </div>

    <form x-show="abierto" x-cloak method="post" action="{{ route('profile.destroy') }}" class="grid gap-4 rounded-xl bg-red-50 border border-red-200 p-4 sm:p-5">
        @csrf
        @method('delete')
        <div class="grid gap-1.5 sm:max-w-sm">
            <label for="password" class="label !text-red-900">Escribe tu contraseña para confirmar</label>
            <input x-ref="clave" id="password" name="password" type="password" class="form-input" autocomplete="current-password">
            <x-input-error :messages="$errors->userDeletion->get('password')" />
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="btn-peligro">Sí, eliminar mi cuenta</button>
            <button type="button" @click="abierto = false" class="btn text-red-900 hover:underline">Cancelar</button>
        </div>
    </form>
</div>
