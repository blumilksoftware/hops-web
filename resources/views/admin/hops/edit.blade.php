<x-admin-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hops-light overflow-hidden shadow-sm sm:rounded-lg p-6 border border-hops-warm">
                <h2 class="font-semibold text-xl text-hops-dark leading-tight mb-6">
                    {{ __('Edit Hop') }}: <span class="text-hops-accent">{{ $hop->name }}</span>
                </h2>

                <form method="POST" action="{{ route('admin.hops.update', $hop) }}" class="space-y-8">
                    @csrf
                    @method('PUT')
                    @include('admin.hops._form')
                    <script>
                        console.log(@js($hop));
                    </script>

                    <div class="flex items-center gap-4 pt-4 border-t border-hops-warm">
                        <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                        <a href="{{ route('admin.hops.index') }}"
                           class="text-sm text-hops-mid hover:text-hops-dark underline"
                        >{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
