@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-warm-200 bg-cream focus:border-rose-primary focus:ring-rose-primary rounded-xl shadow-sm text-warm-900 placeholder-warm-400 transition']) !!}>
