<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 leading-tight italic">Nueva Contraseña</h2>
        <p class="text-slate-400 text-sm font-medium mt-2 leading-relaxed">
            Estás a un paso de recuperar el acceso. Elige una clave segura.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nueva Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('password')" class="mt-2 ml-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <label for="password_confirmation" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirmar Nueva Contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 ml-1" />
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 transition-all transform active:scale-95 flex items-center justify-center">
                Actualizar Contraseña
            </button>
        </div>
    </form>
</x-guest-layout>
