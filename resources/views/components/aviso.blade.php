{{--
    Avisos de la sesión ("Cita reservada correctamente", errores generales).
    Uso: <x-aviso />  (lee session('success') y session('error'))
--}}
@if (session('success'))
    <div {{ $attributes->class(['alert-success']) }} role="status">
        <svg class="w-5 h-5 shrink-0 text-oceano" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif
@if (session('error'))
    <div {{ $attributes->class(['alert-error']) }} role="alert">
        <span>{{ session('error') }}</span>
    </div>
@endif
