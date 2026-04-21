<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hops-dark leading-tight">
            {{ __('Edit User: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hops-light overflow-hidden shadow-sm sm:rounded-lg border border-hops-warm">
                <div class="p-6 text-hops-ink">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <h2 class="text-xl py-4 text-hops-darkest">{{__("User details")}}</h2>

                        <div class="mb-4">
                            <label class="flex flex-col">
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Name') }}</span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="rounded border-hops-warm shadow-sm focus:ring-hops-accent focus:border-hops-accent"
                                >
                                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label class="flex flex-col">
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Email') }}</span>
                                <input type="text" name="email" value="{{ old('email', $user->email) }}"
                                       class="rounded border-hops-warm shadow-sm focus:ring-hops-accent focus:border-hops-accent"
                                >
                                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                            </label>
                        </div>

                        <h2 class="text-xl py-4 text-hops-darkest">{{__("Roles")}}</h2>

                        <div class="mb-4 {{ $user->is_admin ? 'opacity-50' : '' }}">
                            <label class="inline-flex items-center {{ $user->is_admin ? 'cursor-not-allowed' : '' }}">
                                @if ($user->is_admin)
                                    <input type="hidden" name="is_admin" value="1">
                                @endif
                                <input type="checkbox" name="is_admin" value="1"
                                       {{ $user->is_admin ? 'checked disabled readonly' : '' }}
                                       class="rounded border-hops-warm text-hops-accent shadow-sm focus:ring-hops-accent {{ $user->is_admin ? 'cursor-not-allowed' : '' }}"
                                >
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Admin') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_admin')" class="mt-2"/>
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_team_member" value="1"
                                       {{ $user->is_team_member ? 'checked' : '' }}
                                       class="rounded border-hops-warm text-hops-accent shadow-sm focus:ring-hops-accent"
                                >
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Team Member') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_team_member')" class="mt-2"/>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                            <a href="{{ route('admin.users.index') }}"
                               class="text-sm text-hops-dark hover:text-hops-warm"
                            >{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
