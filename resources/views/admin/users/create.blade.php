<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-hops-dark leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-hops-light overflow-hidden shadow-sm sm:rounded-lg border border-hops-warm">
                <div class="p-6 text-hops-ink">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        <h2 class="text-xl py-4 text-hops-darkest">{{__("User details")}}</h2>

                        <div class="mb-4">
                            <label class="flex flex-col">
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Name') }}</span>
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="rounded border-hops-warm shadow-sm focus:ring-hops-accent focus:border-hops-accent"
                                >
                                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label class="flex flex-col">
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Email') }}</span>
                                <input type="text" name="email" value="{{ old('email') }}"
                                       class="rounded border-hops-warm shadow-sm focus:ring-hops-accent focus:border-hops-accent"
                                >
                                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label class="flex flex-col">
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Password') }}</span>
                                <input type="password" name="password" value="{{ old('password') }}"
                                       class="rounded border-hops-warm shadow-sm focus:ring-hops-accent focus:border-hops-accent"
                                >
                                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
                            </label>
                        </div>

                        <h2 class="text-xl py-4 text-hops-darkest">{{__("Roles")}}</h2>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_admin" value="1"
                                       {{ old('is_admin') ? 'checked' : '' }} class="rounded border-hops-warm text-hops-accent shadow-sm focus:ring-hops-accent"
                                >
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Admin') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_team_member" value="1"
                                       {{ old('is_team_member') ? 'checked' : '' }} class="rounded border-hops-warm text-hops-accent shadow-sm focus:ring-hops-accent"
                                >
                                <span class="ml-2 text-sm text-hops-dark">{{ __('Team Member') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_team_member')" class="mt-2"/>
                            <x-input-error :messages="$errors->get('is_admin')" class="mt-2"/>
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
