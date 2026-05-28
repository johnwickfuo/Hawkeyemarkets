<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ config('languages.' . app()->getLocale() . '.rtl') ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page_title ?? 'Auth' }} | {{ getSetting('name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/' . getSetting('favicon')) }}">
    {{-- indexing --}}
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0f172a">
    <meta name="author" content="{{ getSetting('name') }} Team">
    <meta name="publisher" content="{{ getSetting('name') }} Team">
    @if (config('site.use_vite'))
        @vite(['resources/views/templates/bento/css/app.css'])
    @else
        <link rel="stylesheet"
            href="{{ asset('assets/templates/bento/css/main.css' . '?v=' . filemtime(public_path('assets/templates/bento/css/main.css'))) }}">
    @endif
    {!! getSetting('header_scripts') !!}
</head>

<body
    class="bg-primary-dark text-text-primary font-body antialiased selection:bg-accent-orange selection:text-white h-screen overflow-hidden">

    <div class="flex h-full w-full bg-primary-dark relative overflow-hidden">
        {{-- Global Abstract Background Elements --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            {{-- Grid Pattern Overlay --}}
            <div
                class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTTAgNDBMMCAwTDQwIDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsIDI1NSwgMjU1LCAwLjAzKSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIiAvPjwvc3ZnPg==')] opacity-30">
            </div>

            {{-- Dynamic Orbs --}}
            <div
                class="absolute -top-[10%] -left-[10%] w-[70vw] h-[70vw] rounded-full bg-gradient-to-br from-accent-primary/20 via-accent-violet/10 to-transparent blur-[100px] mix-blend-screen animate-pulse-slow">
            </div>
            <div
                class="absolute top-[20%] -right-[10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-bl from-accent-secondary/10 via-accent-cyan/10 to-transparent blur-[90px] mix-blend-screen animate-float">
            </div>
            <div
                class="absolute -bottom-[20%] left-[20%] w-[70vw] h-[70vw] rounded-full bg-gradient-to-t from-accent-glow/10 via-accent-primary/5 to-transparent blur-[110px] mix-blend-screen">
            </div>

            {{-- Additional accent lights --}}
            <div
                class="absolute top-1/3 left-1/2 w-[300px] h-[300px] bg-accent-glow/20 blur-[150px] rounded-full mix-blend-overlay">
            </div>
        </div>

        {{-- Visual Column (Left side on large screens) --}}
        <div class="hidden lg:flex lg:w-1/2 relative items-center justify-center p-12 z-10">
            <div class="relative z-20 max-w-lg text-center">
                @php
                    $quotes = [
                        [
                            'text' => 'The best investment you can make is in yourself.',
                            'author' => 'Warren Buffett',
                            'author_image' => 'warren-buffett.png',
                        ],
                        [
                            'text' =>
                                'The individual investor should act consistently as an investor and not as a speculator.',
                            'author' => 'Benjamin Graham',
                            'author_image' => 'benjamin-graham.png',
                        ],
                        [
                            'text' => 'Know what you own, and know why you own it.',
                            'author' => 'Peter Lynch',
                            'author_image' => 'peter-lynch.png',
                        ],
                        [
                            'text' => "It's not how much money you make, but how much money you keep.",
                            'author' => 'Robert Kiyosaki',
                            'author_image' => 'robert-kiyosaki.png',
                        ],
                        [
                            'text' =>
                                'The stock market is filled with individuals who know the price of everything, but the value of nothing.',
                            'author' => 'Philip Fisher',
                            'author_image' => 'philip-fisher.png',
                        ],
                        [
                            'text' => 'In investing, what is comfortable is rarely profitable.',
                            'author' => 'Robert Arnott',
                            'author_image' => 'robert-arnott.png',
                        ],
                        [
                            'text' =>
                                'How many millionaires do you know who have become wealthy by investing in savings accounts?',
                            'author' => 'Robert G. Allen',
                            'author_image' => 'robert-g-allen.png',
                        ],
                        [
                            'text' => 'Invest in yourself. Your career is the engine of your wealth.',
                            'author' => 'Paul Clitheroe',
                            'author_image' => 'paul-clitheroe.png',
                        ],
                        [
                            'text' => "The four most dangerous words in investing are: This time it's different.",
                            'author' => 'Sir John Templeton',
                            'author_image' => 'john-templeton.png',
                        ],
                        [
                            'text' =>
                                "You get recessions, you have stock market declines. If you don't understand that's going to happen, then you're not ready, you won't do well in the markets.",
                            'author' => 'Peter Lynch',
                            'author_image' => 'peter-lynch.png',
                        ],
                    ];
                    $initialQuote = $quotes[0];
                @endphp

                {{-- 3D Illustration or Hero Image Place holder --}}
                {{-- Active Author Image Container --}}
                <div class="mb-16 relative group cursor-pointer perspective-1000">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-accent-primary via-accent-violet to-accent-secondary blur-3xl opacity-20 rounded-full group-hover:opacity-40 transition-opacity duration-700">
                    </div>

                    <div
                        class="relative z-10 w-80 h-80 mx-auto rounded-full p-2 bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-sm ring-1 ring-white/10 overflow-hidden shadow-2xl animate-float transition-transform duration-500 group-hover:scale-105">
                        @if (isset($initialQuote['author_image']))
                            <img loading="lazy" decoding="async" id="hero-author-image"
                                src="{{ asset('assets/templates/bento/images/authors/' . $initialQuote['author_image']) }}"
                                class="w-full h-full object-cover rounded-full opacity-90 group-hover:opacity-100 transition-opacity duration-500"
                                alt="{{ $initialQuote['author'] }}">
                        @else
                            <div id="hero-author-placeholder"
                                class="w-full h-full flex items-center justify-center bg-accent-primary/20 rounded-full">
                                <span
                                    class="text-6xl font-display font-bold text-accent-primary">{{ substr($initialQuote['author'], 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                </div>



                <div class="relative">
                    {{-- Large decorative quote mark --}}
                    <div
                        class="absolute -top-12 -left-8 text-9xl text-white/5 font-serif font-bold leading-none select-none">
                        "</div>

                    <div id="quote-content"
                        class="relative backdrop-blur-md bg-secondary-dark/30 border border-white/10 p-10 rounded-3xl shadow-2xl overflow-hidden group hover:border-white/20 transition-all duration-500">
                        {{-- Gloss effect --}}
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-white/5 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                        </div>

                        <h2 id="quote-text"
                            class="text-3xl md:text-4xl font-display font-medium text-white mb-8 leading-tight tracking-tight relative z-10 min-h-[120px] flex items-center justify-center">
                            "{{ __($initialQuote['text']) }}"
                        </h2>

                        <div class="flex items-center justify-center gap-5 relative z-10">
                            <div class="h-px w-12 bg-gradient-to-r from-transparent to-accent-primary/50"></div>

                            <div class="flex flex-col items-start">
                                <p id="quote-author"
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-text-primary to-text-secondary font-sans font-bold text-lg tracking-wide uppercase">
                                    {{ __($initialQuote['author']) }}
                                </p>
                            </div>

                            <div class="h-px w-12 bg-gradient-to-l from-transparent to-accent-primary/50"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Column (Right side / Full width on mobile) --}}
        <div
            class="w-full lg:w-1/2 flex flex-col relative z-20 backdrop-blur-md bg-secondary-dark/30 lg:bg-secondary-dark/50 border-l border-white/5 shadow-2xl h-full">

            {{-- Language Switcher --}}
            <div class="absolute top-6 right-6 z-50">
                <div class="relative group" id="lang-switcher">
                    <button id="lang-btn"
                        class="flex items-center gap-2 text-text-secondary hover:text-white transition-all bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/20 rounded-full px-4 py-2 backdrop-blur-sm cursor-pointer"
                        onclick="toggleLanguageMenu(event)">
                        @php
                            $locale = app()->getLocale();
                            $languages = config('languages');
                            $currentLang = $languages[$locale] ?? ['name' => $locale, 'flag' => 'us'];
                        @endphp
                        <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $currentLang['flag'] . '.svg') }}"
                            alt="{{ $currentLang['name'] }}" class="w-4 h-4 rounded-full object-cover">
                        <span class="text-sm font-medium hidden sm:inline-block">{{ $currentLang['name'] }}</span>
                        <svg class="w-3 h-3 text-text-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>

                    <div id="lang-menu"
                        class="absolute right-0 top-full mt-2 w-40 bg-secondary-dark/95 backdrop-blur-xl border border-white/10 rounded-xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right overflow-hidden z-50">
                        <div class="p-1">
                            @foreach ($languages as $code => $lang)
                                <a href="{{ route('lang.switch', $code) }}"
                                    class="flex items-center gap-3 px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/10 rounded-lg transition-colors {{ app()->getLocale() == $code ? 'bg-accent-primary/10 text-accent-primary' : '' }}">
                                    <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $lang['flag'] . '.svg') }}"
                                        alt="{{ $lang['name'] }}" class="w-4 h-4 rounded-full object-cover">
                                    {{ $lang['name'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="flex-1 flex flex-col items-start lg:items-center justify-center p-6 sm:p-12 md:p-20 overflow-y-auto w-full pt-20 lg:pt-12">
                <div class="max-w-[420px] w-full mx-auto">
                    {{-- Brand Header --}}
                    <div class="mb-10 text-center">
                        <a href="{{ route('home') }}" class="inline-block mb-6">
                            <div class="flex items-center justify-center gap-3">
                                {{-- Assuming logo is available --}}
                                @php
                                    $logo = getSetting('logo_rectangle');
                                    $logoPath = public_path('assets/images/' . $logo);
                                    $version = $logo && file_exists($logoPath) ? filemtime($logoPath) : time();
                                @endphp
                                @if ($logo)
                                    <img loading="lazy" decoding="async" src="{{ asset('assets/images/' . $logo) . '?v=' . $version }}"
                                        alt="{{ getSetting('name') }}" class="h-12 w-auto object-contain">
                                @else
                                    <h2 class="text-2xl font-display font-bold text-white tracking-tight">
                                        {{ getSetting('name') }}</h2>
                                @endif
                            </div>
                        </a>
                    </div>

                    {{-- Card Container for Form --}}
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-accent-primary to-accent-secondary rounded-2xl opacity-20 blur transition duration-1000 group-hover:opacity-40 group-hover:duration-200">
                        </div>
                        <div
                            class="relative bg-secondary-dark/80 backdrop-blur-xl border border-white/10 p-8 sm:p-10 rounded-2xl shadow-2xl">
                            @yield('content')
                        </div>
                    </div>
                </div>

                {{-- Mobile Footer (Now Inside Scrollable Flow) --}}
                <div class="mt-12 mb-6 text-center lg:hidden">
                    <p class="font-mono text-text-secondary text-xs opacity-60">© {{ date('Y') }}
                        {{ getSetting('name') }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Mobile Language Switcher Script --}}

    <script>
        function toggleLanguageMenu(event) {
            event.stopPropagation(); // Prevent closing immediately
            const menu = document.getElementById('lang-menu');
            const btn = document.getElementById('lang-btn');

            // Toggle visibility classes manually for mobile support where hover doesn't exist
            if (menu.classList.contains('invisible')) {
                menu.classList.remove('invisible', 'opacity-0');
            } else {
                menu.classList.add('invisible', 'opacity-0');
            }
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('lang-menu');
            const btn = document.getElementById('lang-btn');

            if (!menu.classList.contains('invisible') && !btn.contains(event.target) && !menu.contains(event
                    .target)) {
                menu.classList.add('invisible', 'opacity-0');
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (config('site.use_vite'))
        @vite(['resources/views/templates/bento/js/app.js'])
    @else
        <script
            src="{{ asset('assets/templates/bento/js/main.js' . '?v=' . filemtime(public_path('assets/templates/bento/js/main.js'))) }}">
        </script>
    @endif

    {{-- Notification handler --}}
    @include('templates.bento.blades.partials.notifications')

    {{-- Page Specific Scripts --}}
    @yield('scripts')

    @php
        $jsQuotes = array_map(function ($q) {
            return [
                'text' => __($q['text']),
                'author' => __($q['author']),
                'author_image' => $q['author_image'] ?? null,
            ];
        }, $quotes);
    @endphp

    <script>
        $(document).ready(function() {
            // Pass the quotes array from PHP to JS
            const quotes = @json($jsQuotes);

            // Base asset path for images - assigned from blade to JS variable
            const assetPath = "{{ asset('assets/templates/bento/images/authors/') }}";

            let currentIndex = 0;

            function rotateQuote() {
                currentIndex = (currentIndex + 1) % quotes.length;
                const nextQuote = quotes[currentIndex];

                // Change main hero image with cross-fade
                const $heroImage = $('#hero-author-image');
                const $heroPlaceholder = $('#hero-author-placeholder');

                // Animate image transition
                $heroImage.parent().removeClass('scale-100').addClass('scale-95 opacity-80');

                setTimeout(() => {
                    if (nextQuote.author_image) {
                        $heroImage.attr('src', assetPath + '/' + nextQuote.author_image);
                        $heroImage.show();
                        if ($heroPlaceholder.length) $heroPlaceholder.hide();
                    } else {
                        $heroImage.hide();
                        // If placeholder exists (it might not if first load was image), we'd need to create it or just hiding image is enough if fallback is handled differently.
                        // For simplicity in this logic, we assume image exists for most or simply hide if missing.
                    }

                    // Reset animation classes
                    $heroImage.parent().removeClass('scale-95 opacity-80').addClass(
                        'scale-100 opacity-100');
                }, 500);

                $('#quote-content').fadeOut(500, function() {
                    $('#quote-text').text('"' + nextQuote.text + '"');
                    $('#quote-author').text(nextQuote.author);
                    $(this).fadeIn(500);
                });
            }

            // Rotate every 10 seconds for better visibility
            setInterval(rotateQuote, 10000);
        });
    </script>
    {!! getSetting('livechat_scripts') !!}
    {!! getSetting('footer_scripts') !!}
</body>

</html>
