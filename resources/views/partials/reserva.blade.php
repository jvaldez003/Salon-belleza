{{--
    Cierre de las páginas públicas: "Reserva tu cita" sobre azul abismo.
    Va justo antes del pie de página (también abismo), completando el descenso.
    Requiere $urlReserva (lo comparte AppServiceProvider).
--}}
@php
    $waReserva = $configuracionSitio->whatsapp_numero;
@endphp
<section class="bg-abismo" aria-labelledby="titulo-reserva">
    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-16 md:py-28 grid gap-6">
        <h2 id="titulo-reserva" class="text-white text-[clamp(2.4rem,1.4rem+5vw,5rem)] leading-[1.02] max-w-[14ch]">{{ $titulo ?? 'Reserva tu cita' }}</h2>
        <p class="text-sobre-abismo-suave text-lg md:text-xl leading-relaxed max-w-[44ch]">
            Elige el servicio, el día y la hora.
            @guest Crea tu cuenta en un minuto para hacer y consultar tus reservas. @endguest
        </p>
        <div class="flex flex-wrap gap-3 pt-2">
            <x-boton variante="claro" :href="$urlReserva">Reservar cita</x-boton>
            @if ($waReserva)
                <a href="https://wa.me/{{ $waReserva }}?text={{ urlencode($mensajeWhatsapp ?? 'Hola, me gustaría reservar una cita') }}" target="_blank" rel="noopener"
                   class="btn border-sobre-abismo-suave/40 text-sobre-abismo hover:bg-white/10">Reservar por WhatsApp</a>
            @endif
        </div>
    </div>
</section>
