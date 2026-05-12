<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-hops-light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('Hop Variety Browser') }} - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
</head>

<body class="h-full bg-hops-light text-hops-ink font-sans antialiased">
    <div class="min-h-full flex flex-col">
        <x-hops.navigation />

        <main class="grow">
            {{ $slot }}
        </main>

        <footer class="bg-hops-ink text-white pt-12 pb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
                    <div class="text-2xl font-bold flex items-center">
                        <x-lucide-hop class="w-6 h-6" />
                        <span>&nbsp;hops</span>
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-center space-x-0 md:space-x-6 space-y-4 md:space-y-0 text-sm font-medium">
                        <a href="#" class="hover:text-hops-accent transition">{{ __('About us') }}</a>
                        <a href="#" class="hover:text-hops-accent transition">{{ __('Projects') }}</a>
                        <a href="#" class="hover:text-hops-accent transition">{{ __('Contact') }}</a>
                        <a href="#" class="hover:text-hops-accent transition">{{ __('Privacy Policy') }}</a>
                        <div
                            class="flex items-center space-x-3 ml-0 md:ml-4 border-l-0 md:border-l border-gray-700 pl-0 md:pl-6">
                            <a href="https://github.com/blumilksoftware/hops-web" target="_blank">
                                <svg id='brand-github_24' width='24' height='24' viewBox='0 0 24 24'
                                    xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                                    <rect width='24' height='24' stroke='none' fill='#000000' opacity='0' />
                                    <g transform="matrix(1 0 0 1 12 12)">
                                        <g style="">
                                            <g transform="matrix(1 0 0 1 0 0)">
                                                <path
                                                    style="stroke: none; stroke-width: 2; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                                    transform=" translate(-12, -12)" d="M 0 0 L 24 0 L 24 24 L 0 24 z"
                                                    stroke-linecap="round" />
                                            </g>
                                            <g transform="matrix(1 0 0 1 -0.5 0.04)">
                                                <path
                                                    style="stroke: rgb(255,255,255); stroke-width: 2; stroke-dasharray: none; stroke-linecap: round; stroke-dashoffset: 0; stroke-linejoin: round; stroke-miterlimit: 4; fill: none; fill-rule: nonzero; opacity: 1;"
                                                    transform=" translate(-11.5, -12.04)"
                                                    d="M 9 19 C 4.7 20.4 4.7 16.5 3 16 M 15 21 L 15 17.5 C 15 16.5 15.1 16.1 14.5 15.5 C 17.3 15.2 20 14.1 20 9.5 C 19.998782483826893 8.304964158798457 19.532547228459077 7.157308145585369 18.700000000000003 6.300000000000001 C 19.090466912267523 5.2619664215772275 19.054518829530473 4.111627773991587 18.599999999999998 3.1000000000000014 C 18.599999999999998 3.0999999999999996 17.499999999999996 2.8 15.099999999999998 4.3999999999999995 C 13.067237849012272 3.8705877507909445 10.932762150987713 3.8705877507909454 8.89999999999999 4.400000000000003 C 6.499999999999998 2.7999999999999994 5.399999999999999 3.0999999999999996 5.399999999999999 3.0999999999999996 C 4.945481170469526 4.111627773991585 4.909533087732473 5.2619664215772275 5.299999999999999 6.3 C 4.467452771540924 7.157308145585368 4.0012175161731065 8.304964158798457 3.9999999999999987 9.5 C 3.999999999999999 14.1 6.699999999999999 15.2 9.5 15.5 C 8.9 16.1 8.9 16.7 9 17.5 L 9 21"
                                                    stroke-linecap="round" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="text-sm border-t border-gray-700 pt-6 text-gray-400">
                    Copyright {{ date('Y') }} | Blumilk sp. z o.o.
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
