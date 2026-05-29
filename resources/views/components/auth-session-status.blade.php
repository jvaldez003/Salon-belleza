@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-rose-dark bg-rose-light/20 rounded-xl px-4 py-3']) }}>
        {{ $status }}
    </div>
@endif
