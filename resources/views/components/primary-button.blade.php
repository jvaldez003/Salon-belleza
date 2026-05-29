<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-rose-primary border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-rose-dark focus:bg-rose-dark active:bg-rose-deeper focus:outline-none focus:ring-2 focus:ring-rose-primary focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
