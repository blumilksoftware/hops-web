<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hops-light overflow-hidden shadow-sm sm:rounded-lg p-6 border border-hops-warm">
                <h2 class="font-semibold text-xl text-hops-dark leading-tight">
                    {{ __('Hops') }}
                </h2>
                <div class="p-6 text-hops-ink">
                    <div class="mb-4 flex justify-between items-center">
                        <form method="GET" action="{{ route('admin.hops.index') }}" class="flex items-center gap-2">
                            <div class="relative flex items-center" x-data="{ search: '{{ request('search') }}' }">
                                <x-text-input x-ref="searchInput" name="search" x-model="search"
                                    placeholder="{{ __('Search hops...') }}" class="pr-8" />
                                <button type="button" x-show="search" @click="search = ''; $refs.searchInput.focus()"
                                    style="display: none;"
                                    class="absolute right-2 hover:cursor-pointer text-gray-400 hover:text-gray-600">
                                    <x-eva-close class="w-6 h-6" />
                                </button>
                            </div>
                            <x-primary-button>{{ __('Search') }}</x-primary-button>
                            <a href="{{ route('admin.hops.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-hops-accent border border-hops-accent rounded-md font-semibold text-xs text-hops-darkest uppercase tracking-widest shadow-sm hover:bg-hops-warm hover:border-hops-warm focus:outline-none focus:ring-2 focus:ring-hops-accent focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Create') }}
                            </a>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-hops-warm">
                            <thead class="bg-hops-light">
                                <tr class="text-nowrap">
                                    <th
                                        class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Name') }}
                                    </th>
                                    <th
                                        class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Slug') }}
                                    </th>
                                    <th
                                        class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Alt Name') }}
                                    </th>
                                    <th
                                        class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Country') }}
                                    </th>
                                    <th
                                        class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Created At') }}
                                    </th>
                                    <th
                                        class="px-6 py-6 text-right text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-hops-light divide-y divide-hops-warm">
                                @empty($hops->count())
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-hops-mid">
                                            {{ __('No hops found.') }}
                                        </td>
                                    </tr>
                                @endempty
                                @foreach ($hops as $hop)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-hops-dark">
                                            {{ $hop->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hops-mid font-mono">
                                            {{ $hop->slug }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hops-mid">
                                            {{ $hop->alt_name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hops-mid">
                                            {{ $hop->country ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hops-mid">
                                            {{ $hop->created_at?->format('Y-m-d') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('admin.hops.edit', $hop) }}">
                                                    <x-far-edit class="w-6 h-6 hover:cursor-pointer text-blue-500"
                                                        alt="{{ __('Edit hop') }}" />
                                                </a>

                                                <form action="{{ route('admin.hops.destroy', $hop) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('{{ __('Are you sure you want to delete') }} {{ $hop->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit">
                                                        <x-ri-delete-bin-6-line
                                                            class="w-6 h-6 hover:cursor-pointer text-red-500"
                                                            alt="{{ __('Delete hop') }}" />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $hops->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
