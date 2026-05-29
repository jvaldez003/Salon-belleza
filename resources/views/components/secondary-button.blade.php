<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-5 py-2.5 bg-white border border-warm-200 rounded-xl font-bold text-sm text-warm-700 uppercase tracking-widest shadow-sm hover:bg-cream focus:outline-none focus:ring-2 focus:ring-rose-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
