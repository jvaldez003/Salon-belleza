<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 leading-tight">Únete a nosotros</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Crea tu cuenta para empezar a disfrutar del lujo.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div class="space-y-1">
            <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nombre Completo</label>
            <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <label for="password_confirmation" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirmar Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 transition-all transform active:scale-95 flex items-center justify-center">
                Crear Mi Cuenta
            </button>
        </div>

        <p class="text-center text-sm font-bold text-slate-400 pt-4">
            ¿Ya tienes una cuenta? 
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700">Inicia sesión</a>
        </p>
    </form>
</x-guest-layout>
