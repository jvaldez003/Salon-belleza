<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 leading-tight italic">Verifica tu correo</h2>
        <p class="text-slate-400 text-sm font-medium mt-2 leading-relaxed">
            Gracias por registrarte. Antes de continuar, confirma tu dirección de correo haciendo clic en el enlace que te enviamos.
            Si no lo recibiste, puedes solicitar otro.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-2xl border border-emerald-100 font-bold text-sm">
            Te enviamos un nuevo enlace de verificación a tu correo electrónico.
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-100 transition-all">
                Reenviar correo de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-black py-4 rounded-2xl transition-all">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
