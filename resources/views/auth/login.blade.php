<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <h2 class="text-3xl font-black text-warm-900 leading-tight">Bienvenido de nuevo</h2>
        <p class="text-warm-400 text-sm font-medium mt-1">Ingresa tus credenciales para continuar.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="space-y-1">
            <label for="email" class="label ml-1">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   class="w-full bg-cream border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-rose-primary transition-all font-bold text-warm-900">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <label for="password" class="label ml-1">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-cream border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-rose-primary transition-all font-bold text-warm-900">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between ml-1">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-warm-200 text-rose-primary focus:ring-rose-primary" name="remember">
                <span class="ml-3 text-sm font-bold text-warm-500 uppercase tracking-wider">Recuérdame</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-xs font-black text-rose-dark hover:text-rose-deeper uppercase tracking-widest" href="{{ route('password.request') }}">
                    ¿Olvidaste tu clave?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-rose-primary hover:bg-rose-dark text-white font-black py-4 rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center">
                Iniciar Sesión
            </button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm font-bold text-warm-400 pt-6">
                ¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-rose-dark hover:text-rose-deeper">Regístrate gratis</a>
            </p>
        @endif
    </form>
</x-guest-layout>
