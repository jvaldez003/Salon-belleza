<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 leading-tight italic">¿Olvidaste tu clave?</h2>
        <p class="text-slate-400 text-sm font-medium mt-2 leading-relaxed">
            No te preocupes. Dinos tu correo y te enviaremos un enlace para que elijas una nueva contraseña de forma segura.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 bg-emerald-50 text-emerald-600 p-4 rounded-2xl border border-emerald-100 font-bold text-sm" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Correo Electrónico</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                   class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-800">
            <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 transition-all transform active:scale-95 flex items-center justify-center">
                Enviar Enlace de Recuperación
            </button>
        </div>

        <p class="text-center text-sm font-bold text-slate-400 pt-4">
            <a href="{{ route('login') }}" class="text-slate-900 hover:text-indigo-600 flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
                Volver al inicio de sesión
            </a>
        </p>
    </form>
</x-guest-layout>
