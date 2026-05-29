@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-warm-700']) }}>
    {{ $value ?? $slot }}
</label>
