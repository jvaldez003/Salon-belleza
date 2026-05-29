<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-warm-900 leading-tight">Únete a nosotros</h2>
        <p class="text-warm-400 text-sm font-medium mt-1">Crea tu cuenta para empezar a disfrutar del lujo.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="space-y-1">
            <label for="name" class="label ml-1">Nombre Completo</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                   class="w-full bg-cream border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-rose-primary transition-all font-bold text-warm-900">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <label for="email" class="label ml-1">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                   class="w-full bg-cream border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-rose-primary transition-all font-bold text-warm-900">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <label for="password" class="label ml-1">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-cream border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-rose-primary transition-all font-bold text-warm-900">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="space-y-1">
            <label for="password_confirmation" class="label ml-1">Confirmar Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-cream border-0 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-rose-primary transition-all font-bold text-warm-900">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-rose-primary hover:bg-rose-dark text-white font-black py-4 rounded-2xl shadow-sm transition-all active:scale-95 flex items-center justify-center">
                Crear Mi Cuenta
            </button>
        </div>

        <p class="text-center text-xs text-warm-400 pt-4 leading-relaxed">
            Al registrarte recibirás un correo para confirmar tu cuenta antes de acceder al sistema.
        </p>

        <p class="text-center text-sm font-bold text-warm-400 pt-4">
            ¿Ya tienes una cuenta?
            <a href="{{ route('login') }}" class="text-rose-dark hover:text-rose-deeper">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>
