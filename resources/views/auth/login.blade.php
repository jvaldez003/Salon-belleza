<x-guest-layout titulo="Entrar">
    <div class="grid gap-2 mb-8">
        <h1 class="text-[2.6rem] leading-none">Hola de nuevo</h1>
        <p class="text-texto-suave">Entra para reservar y ver tus citas.</p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="grid gap-5">
        @csrf

        <div class="grid gap-1.5">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="grid gap-1.5">
            <div class="flex items-baseline justify-between gap-4">
                <x-input-label for="password" value="Contraseña" />
                @if (Route::has('password.request'))
                    <a class="text-sm text-oceano hover:underline underline-offset-4" href="{{ route('password.request') }}">¿La olvidaste?</a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <label for="remember_me" class="inline-flex items-center gap-3 text-[0.9375rem] text-texto cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 rounded border-linea text-oceano focus:ring-laguna">
            Mantener la sesión iniciada
        </label>

        <button type="submit" class="btn-primary w-full mt-2">Entrar</button>
    </form>

    @if (Route::has('register'))
        <p class="mt-8 pt-6 border-t border-linea text-[0.9375rem] text-texto">
            ¿Primera vez en Arrecife?
            <a href="{{ route('register') }}" class="font-semibold text-oceano hover:underline underline-offset-4">Crea tu cuenta</a>
        </p>
    @endif
</x-guest-layout>
