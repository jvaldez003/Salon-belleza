@extends('errors.marco')
@section('codigo', '403')
@section('titulo', 'No tienes acceso a esta página')
@section('mensaje', 'Tu cuenta no tiene permiso para ver esto. Si crees que es un error, pídele acceso al administrador del salón.')
@section('acciones')<a href="{{ url('/dashboard') }}" class="btn-primary">Ir a mi panel</a><a href="{{ url('/') }}" class="btn-secondary">Ir al inicio</a>@endsection
