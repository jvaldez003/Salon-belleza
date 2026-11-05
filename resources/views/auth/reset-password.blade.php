<x-guest-layout titulo="Nueva contraseña">
    <div class="grid gap-2 mb-8">
        <h1 class="text-[2.6rem] leading-none">Crea una nueva contraseña</h1>
        <p class="text-texto-suave">Elige una contraseña que no hayas usado antes.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="grid gap-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="grid gap-1.5">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="grid gap-1.5">
            <x-input-label for="password" value="Nueva contraseña" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <p class="text-xs text-texto-suave">Mínimo 8 caracteres.</p>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="grid gap-1.5">
            <x-input-label for="password_confirmation" value="Repite la contraseña" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit" class="btn-primary w-full mt-2">Guardar contraseña</button>
    </form>
</x-guest-layout>
