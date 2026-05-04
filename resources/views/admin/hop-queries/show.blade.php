<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-hops-dark leading-tight">
                {{ __('Hop Query Details #') }}{{ $hopQuery->id }}
            </h2>
            <a href="{{ route('admin.hop-queries.index') }}" class="text-sm text-hops-accent hover:underline">
                &larr; {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-hops-light overflow-hidden shadow-sm sm:rounded-lg p-6 border border-hops-warm">
                <h3 class="text-lg font-medium text-hops-darkest mb-4">{{ __('Query Information') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-hops-ink">
                    <div>
                        <strong class="text-hops-darkest">{{ __('User:') }}</strong> {{ $hopQuery->user->name }} ({{ $hopQuery->user->email }})
                    </div>
                    <div>
                        <strong class="text-hops-darkest">{{ __('Date:') }}</strong> {{ $hopQuery->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-hops-light border border-hops-warm overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-hops-darkest mb-4">{{ __('Query Payload') }}</h3>
                    <div class="bg-gray-50 border border-hops-warm p-4 rounded-md overflow-auto max-h-96 text-sm font-mono whitespace-pre text-hops-ink">@php
$queryData = is_array($hopQuery->query) ? $hopQuery->query : json_decode($hopQuery->query ?? '{}', true);
@endphp
{{ json_encode($queryData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</div>
                </div>

                <div class="bg-hops-light border border-hops-warm overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-hops-darkest mb-4">{{ __('Response Payload') }}</h3>
                    @if($hopQuery->response)
                        <div class="bg-gray-50 border border-hops-warm p-4 rounded-md overflow-auto max-h-96 text-sm font-mono whitespace-pre text-hops-ink">@php
$responseData = is_array($hopQuery->response) ? $hopQuery->response : json_decode($hopQuery->response, true);
@endphp
{{ json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</div>
                    @else
                        <div class="bg-gray-50 border border-hops-warm p-4 rounded-md text-sm text-hops-ink">
                            <span class="text-gray-500 italic">{{ __('No response recorded.') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
