<x-guest-layout titulo="Confirma tu contraseña">
    <div class="grid gap-2 mb-8">
        <h1 class="text-[2.6rem] leading-none">Confirma tu contraseña</h1>
        <p class="text-texto-suave">Es una sección protegida. Escribe tu contraseña para continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="grid gap-5">
        @csrf
        <div class="grid gap-1.5">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <button type="submit" class="btn-primary w-full mt-2">Continuar</button>
    </form>
</x-guest-layout>
