@extends('errors.marco')
@section('codigo', '503')
@section('titulo', 'Estamos haciendo mejoras')
@section('mensaje', 'El sitio vuelve en unos minutos. Gracias por la paciencia.')
@section('acciones')<a href="{{ url('/') }}" class="btn-primary">Intentar de nuevo</a>@endsection
