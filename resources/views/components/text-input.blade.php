@props(['disabled' => false])

<input @disabled($disabled) {!! $attributes->merge(['class' => 'form-input disabled:bg-bruma disabled:text-texto-suave']) !!}>
