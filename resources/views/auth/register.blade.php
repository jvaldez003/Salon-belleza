<x-guest-layout titulo="Crear cuenta">
    <div class="grid gap-2 mb-8">
        <h1 class="text-[2.6rem] leading-none">Crea tu cuenta</h1>
        <p class="text-texto-suave">Con tu cuenta reservas en un minuto y consultas tus citas cuando quieras.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="grid gap-5">
        @csrf

        <div class="grid gap-1.5">
            <x-input-label for="name" value="Nombre completo" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="grid gap-1.5">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <p class="text-xs text-texto-suave">Te enviaremos un enlace para confirmarlo.</p>
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="grid gap-1.5">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <p class="text-xs text-texto-suave">Mínimo 8 caracteres.</p>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="grid gap-1.5">
            <x-input-label for="password_confirmation" value="Repite la contraseña" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit" class="btn-primary w-full mt-2">Crear cuenta</button>
    </form>

    <p class="mt-8 pt-6 border-t border-linea text-[0.9375rem] text-texto">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="font-semibold text-oceano hover:underline underline-offset-4">Entra aquí</a>
    </p>
</x-guest-layout>
