@extends('errors.marco')
@section('codigo', '419')
@section('titulo', 'Tu sesión expiró')
@section('mensaje', 'Pasó mucho tiempo desde que abriste la página y, por seguridad, no se guardó nada. Vuelve atrás, recarga la página y envía el formulario otra vez.')
@section('acciones')<a href="javascript:history.back()" class="btn-primary">Volver al formulario</a><a href="{{ url('/') }}" class="btn-secondary">Ir al inicio</a>@endsection
