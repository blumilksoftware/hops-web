<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hops-light overflow-hidden shadow-sm sm:rounded-lg p-6 border border-hops-warm">
                <h2 class="font-semibold text-xl text-hops-dark leading-tight">
                    {{ __('Users') }}
                </h2>
                <div class="p-6 text-hops-ink">
                    <div class="mb-4 flex justify-between items-center">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                            <div class="relative flex items-center" x-data="{ search: '{{ request('search') }}' }">
                                <x-text-input x-ref="searchInput" name="search" x-model="search"
                                              placeholder="Search users..." class="pr-8"
                                />
                                <button type="button" x-show="search" @click="search = ''; $refs.searchInput.focus()"
                                        style="display: none;"
                                        class="absolute right-2 text-gray-400 hover:text-gray-600"
                                >
                                <x-eva-close class="w-6 h-6" />
                                </button>
                            </div>
                            <x-primary-button>Search</x-primary-button>
                            <a href="{{ route('admin.users.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-hops-accent border border-hops-accent rounded-md font-semibold text-xs text-hops-darkest uppercase tracking-widest shadow-sm hover:bg-hops-warm hover:border-hops-warm focus:outline-none focus:ring-2 focus:ring-hops-accent focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                {{ __('Create') }}
                            </a>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-hops-warm">
                            <thead class="bg-hops-light">
                            <tr>
                                <th
                                    class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider"
                                >
                                    Email
                                </th>
                                <th
                                    class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider"
                                >
                                    Roles
                                </th>
                                <th
                                    class="px-6 py-6 text-left text-xs font-semibold text-hops-darkest uppercase tracking-wider"
                                >
                                    Created At
                                </th>
                                <th
                                    class="px-6 py-6 text-right text-xs font-semibold text-hops-darkest uppercase tracking-wider"
                                >
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-hops-light divide-y divide-hops-warm">
                            @if($users->isEmpty())
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 whitespace-nowrap text-center text-sm text-hops-mid"
                                    >
                                        No users found.
                                    </td>
                                </tr>
                            @else
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap flex justify-between items-center">
                                            {{ $user->name }}
                                            @if ($user->isUserCurrentlyAuthenticated())
                                                <span
                                                    class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full border border-gray-400 text-gray-800"
                                                >You</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->is_admin)
                                                <span
                                                    class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                                                >Admin</span>
                                            @endif
                                            @if ($user->is_team_member)
                                                <span
                                                    class="px-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"
                                                >Team
                                                    Member</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-hops-mid">
                                            {{ $user->created_at }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-4">

                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                >
                                                    <x-fluentui-person-edit-24-o
                                                        class="w-8 h-8 hover:cursor-pointer text-blue-500"
                                                        alt="Edit user"
                                                    />
                                                </a>

                                                @if (!$user->is_admin)
                                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                                          method="POST" class="inline-block"
                                                          onsubmit="return confirm('Are you sure to delete {{$user->name}}?')"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit">
                                                            <x-antdesign-user-delete-o
                                                                class="w-8 h-8 hover:cursor-pointer text-red-500"
                                                                alt="Delete user"
                                                            />
                                                        </button>
                                                    </form>
                                                @else
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links('components.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
