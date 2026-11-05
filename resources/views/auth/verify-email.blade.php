<x-guest-layout titulo="Confirma tu correo">
    <div class="grid gap-3 mb-8">
        <h1 class="text-[2.6rem] leading-none">Confirma tu correo</h1>
        <p class="text-texto leading-relaxed">
            Te enviamos un enlace a <strong class="text-tinta">{{ auth()->user()->email }}</strong>.
            Ábrelo para activar tu cuenta{{ session('url.intended') ? ' y continuar con tu reserva' : '' }}.
        </p>
        <p class="text-sm text-texto-suave">Si no lo ves en unos minutos, revisa la carpeta de correo no deseado.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert-success mb-6" role="status">Te enviamos un enlace nuevo. Revisa tu correo.</div>
    @endif

    <div class="grid gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full">Enviar el enlace otra vez</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-secondary w-full">Cerrar sesión</button>
        </form>
    </div>
</x-guest-layout>
