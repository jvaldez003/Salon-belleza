@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert-success text-[0.9375rem]']) }} role="status">
        {{ $status }}
    </div>
@endif
