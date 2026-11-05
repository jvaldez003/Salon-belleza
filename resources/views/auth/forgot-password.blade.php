<x-guest-layout titulo="Recuperar contraseña">
    <div class="grid gap-2 mb-8">
        <h1 class="text-[2.6rem] leading-none">Recupera tu contraseña</h1>
        <p class="text-texto-suave">Escribe el correo con el que te registraste y te enviaremos un enlace para crear una nueva.</p>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="grid gap-5">
        @csrf
        <div class="grid gap-1.5">
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>
        <button type="submit" class="btn-primary w-full mt-2">Enviar enlace</button>
    </form>

    <p class="mt-8 pt-6 border-t border-linea text-[0.9375rem]">
        <a href="{{ route('login') }}" class="font-semibold text-oceano hover:underline underline-offset-4">Volver a entrar</a>
    </p>
</x-guest-layout>
