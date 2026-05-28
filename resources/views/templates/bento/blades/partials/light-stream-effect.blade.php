{{-- Light Stream Effect --}}
{{-- Can be included in any relative container. Assumes parent has relative positioning. --}}
<div class="absolute inset-0 pointer-events-none z-0">
    <svg class="w-full h-full" viewBox="0 0 1440 900" preserveAspectRatio="none" fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <defs>
            {{-- Gradient for the Stream --}}
            <linearGradient id="stream-gradient-left" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="rgba(124, 58, 237, 0)" /> {{-- Transparent Tail --}}
                <stop offset="50%" stop-color="rgba(124, 58, 237, 0.5)" />
                <stop offset="100%" stop-color="#7c3aed" /> {{-- Bright Head (Purple) --}}
            </linearGradient>

            <linearGradient id="stream-gradient-right" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="rgba(59, 130, 246, 0)" />
                <stop offset="50%" stop-color="rgba(59, 130, 246, 0.5)" />
                <stop offset="100%" stop-color="#3b82f6" /> {{-- Bright Head (Blue) --}}
            </linearGradient>

            {{-- Glow Filter --}}
            <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stdDeviation="4" result="coloredBlur" />
                <feMerge>
                    <feMergeNode in="coloredBlur" />
                    <feMergeNode in="SourceGraphic" />
                </feMerge>
            </filter>
        </defs>

        {{-- Left Stream Paths (5 Strands) --}}
        @for ($i = 0; $i < 5; $i++)
            @php
                $offset = $i * 12; // Horizontal Gap
                $yStart = 50 + $i * 15; // Spread logic: Start closer to top (50) and fan down
                $delay = $i * 0.2;
                $duration = 3 + $i * 0.1;
            @endphp
            {{-- Start Y varies, Curve Control Y fixed or varies, End Y fixed --}}
            {{-- Shape: Start at varying Y (-50 x), Go right to 50, Curve Down to 150,400 --}}
            {{-- We adjust the first horizontal leg to match the new Y Start --}}
            <path class="stream-path-left"
                d="M{{ -50 }} {{ $yStart }} L {{ 50 + $offset }} {{ $yStart }} Q {{ 150 + $offset }} {{ $yStart }} {{ 150 + $offset }} 400 L {{ 150 + $offset }} 950"
                stroke="url(#stream-gradient-left)" stroke-width="2" fill="none"
                style="filter: url(#glow); animation-delay: {{ $delay }}s; animation-duration: {{ $duration }}s;" />
        @endfor

        {{-- Right Stream Paths (5 Strands) --}}
        @for ($i = 0; $i < 5; $i++)
            @php
                $offset = $i * 12;
                $yStart = 50 + $i * 15;
                $delay = 0.5 + $i * 0.2;
                $duration = 3.5 + $i * 0.1;
            @endphp
            <path class="stream-path-right"
                d="M{{ 1490 }} {{ $yStart }} L {{ 1390 - $offset }} {{ $yStart }} Q {{ 1290 - $offset }} {{ $yStart }} {{ 1290 - $offset }} 400 L {{ 1290 - $offset }} 950"
                stroke="url(#stream-gradient-right)" stroke-width="2" fill="none"
                style="filter: url(#glow); animation-delay: {{ $delay }}s; animation-duration: {{ $duration }}s;" />
        @endfor

        {{-- Static faint lines for wireframe feel --}}
        @for ($i = 0; $i < 5; $i++)
            @php $offset = $i * 12; @endphp
            <path
                d="M{{ -50 + $offset }} 100 L {{ 50 + $offset }} 100 Q {{ 150 + $offset }} 100 {{ 150 + $offset }} 400 L {{ 150 + $offset }} 950"
                stroke="white" stroke-width="1" stroke-opacity="0.03" fill="none" />
            <path
                d="M{{ 1490 - $offset }} 100 L {{ 1390 - $offset }} 100 Q {{ 1290 - $offset }} 100 {{ 1290 - $offset }} 400 L {{ 1290 - $offset }} 950"
                stroke="white" stroke-width="1" stroke-opacity="0.03" fill="none" />
        @endfor
    </svg>
</div>

<style>
    .stream-path-left {
        stroke-dasharray: 400 1000;
        /* Dash Length, Gap Length */
        stroke-dashoffset: 1400;
        /* Start hidden (pushed back) */
        animation: flowLeft 3s ease-in-out infinite;
    }

    .stream-path-right {
        stroke-dasharray: 400 1000;
        stroke-dashoffset: 1400;
        animation: flowRight 3.5s ease-in-out infinite;
        animation-delay: 0.5s;
        /* Offset start */
    }

    @keyframes flowLeft {
        0% {
            stroke-dashoffset: 1400;
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            stroke-dashoffset: -400;
            /* Move past the end */
            opacity: 0;
        }
    }

    @keyframes flowRight {
        0% {
            stroke-dashoffset: 1400;
            opacity: 0;
        }

        10% {
            opacity: 1;
        }

        90% {
            opacity: 1;
        }

        100% {
            stroke-dashoffset: -400;
            opacity: 0;
        }
    }
</style>
