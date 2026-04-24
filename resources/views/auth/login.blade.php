<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 leading-tight">Bienvenido de nuevo</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Ingresa tus credenciales para continuar.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between ml-1">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="w-5 h-5 rounded-lg border-slate-200 text-indigo-600 focus:ring-indigo-500" name="remember">
                <span class="ml-3 text-sm font-bold text-slate-500 uppercase tracking-wider">Recuérdame</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-xs font-black text-indigo-600 hover:text-indigo-700 uppercase tracking-widest" href="{{ route('password.request') }}">
                    ¿Olvidaste tu clave?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 transition-all transform active:scale-95 flex items-center justify-center">
                Iniciar Sesión
            </button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm font-bold text-slate-400 pt-6">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700">Regístrate gratis</a>
            </p>
        @endif
    </form>
</x-guest-layout>
