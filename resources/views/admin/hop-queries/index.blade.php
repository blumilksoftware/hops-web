<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hops-light overflow-hidden shadow-sm sm:rounded-lg p-6 border border-hops-warm">
                <h2 class="font-semibold text-xl text-hops-dark leading-tight mb-6">
                    {{ __('Hop Queries') }}
                </h2>
                <div class="text-hops-ink">
                    <div class="mb-4 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <form method="GET" action="{{ route('admin.hop-queries.index') }}" class="flex flex-wrap items-center gap-2">
                            <div class="relative flex items-center" x-data="{ search: '{{ request('user') }}' }">
                                <x-text-input x-ref="searchInput" name="user" x-model="search"
                                    placeholder="{{ __('Username/email...') }}" class="pr-8" />
                                <button type="button" x-show="search" @click="search = ''; $refs.searchInput.focus()"
                                    style="display: none;"
                                    class="absolute right-2 hover:cursor-pointer text-gray-400 hover:text-gray-600">
                                    <x-eva-close class="w-6 h-6" />
                                </button>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-hops-mid">{{ __('From:') }}</span>
                                <x-text-input type="date" name="date_from" value="{{ request('date_from') }}" onchange="this.form.submit()" />
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-hops-mid">{{ __('To:') }}</span>
                                <x-text-input type="date" name="date_to" value="{{ request('date_to') }}" onchange="this.form.submit()" />
                            </div>

                            
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-hops-warm">
                            <thead class="bg-hops-light">
                                <tr class="text-nowrap">
                                    <th class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('ID') }}
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('User') }}
                                    </th>
                                    <th class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Date') }}
                                    </th>
                                    <th class="px-6 py-6 text-right text-xs font-semibold text-hops-darkest uppercase tracking-wider">
                                        {{ __('Actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-hops-light divide-y divide-hops-warm">
                                @empty($hopQueries->count())
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 whitespace-nowrap text-center text-sm text-hops-mid">
                                            {{ __('No hop queries found.') }}
                                        </td>
                                    </tr>
                                @endempty
                                @foreach ($hopQueries as $query)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hops-mid font-mono">
                                            #{{ $query->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-hops-dark">
                                            {{ $query->user->name }}
                                            <br>
                                            <span class="text-xs text-hops-mid font-normal">{{ $query->user->email }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hops-mid">
                                            {{ $query->created_at?->format('d/m/Y H:i') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('admin.hop-queries.show', $query) }}">
                                                    <x-far-eye class="w-6 h-6 hover:cursor-pointer text-blue-500"
                                                        alt="{{ __('View details') }}" />
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $hopQueries->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
