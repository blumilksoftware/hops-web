<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-hops-light border border-hops-warm rounded-md font-semibold text-xs text-hops-dark uppercase tracking-widest shadow-sm hover:bg-hops-accent focus:outline-none focus:ring-2 focus:ring-hops-accent focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
