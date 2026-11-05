{{-- Reserva de cita para clientas: con el diseño público de Arrecife (menú y pie). --}}
@extends('layouts.public')

@section('title', $configuracionSitio->nombre_negocio ?? 'Arrecife')
@section('subtitle', isset($cita) ? 'Cambiar mi cita' : 'Reservar cita')
{{-- La barra de resumen inferior ocupa el lugar del botón flotante de WhatsApp --}}
@section('sin_whatsapp_flotante', '1')

@section('content')
<section class="bg-gradient-to-b from-superficie to-bruma">
    <div class="max-w-arrecife mx-auto px-4 sm:px-6 pt-10 md:pt-16 pb-16 md:pb-24">
        <div class="grid gap-3 mb-10 md:mb-14">
            <h1 class="opsz-max text-[clamp(2.5rem,1.4rem+4.5vw,5rem)] leading-[.95]">
                {{ isset($cita) ? 'Cambiar mi cita' : 'Reserva tu cita' }}
            </h1>
            <p class="text-lg leading-relaxed max-w-[48ch]">
                Elige tus servicios, el día y la hora. Te confirmamos la cita por WhatsApp.
            </p>
        </div>

        @include('citas._form', [
            'cita'   => $cita ?? null,
            'action' => isset($cita) ? route('citas.update', $cita) : route('citas.store'),
            'method' => isset($cita) ? 'PUT' : 'POST',
            'fechaInicial' => isset($cita) ? null : request('fecha'),
        ])
    </div>
</section>
@endsection
