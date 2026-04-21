<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-hops-dark border border-transparent rounded-md font-semibold text-xs text-hops-light uppercase tracking-widest hover:bg-hops-mid focus:bg-hops-mid active:bg-hops-darkest focus:outline-none focus:ring-2 focus:ring-hops-accent focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
