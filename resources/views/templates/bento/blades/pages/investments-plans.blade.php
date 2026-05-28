@extends('templates.bento.blades.layouts.front')

@section('title', $page_title . ' - ' . getSetting('name'))
@section('page_title', $page_title)

@section('content')
    <style>
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(2deg);
            }
        }

        .animate-float {
            animation: float 10s ease-in-out infinite;
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.3;
                transform: scale(1);
            }

            50% {
                opacity: 0.6;
                transform: scale(1.05);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 12s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes loading-bar {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(200%);
            }
        }

        .animate-loading-bar {
            animation: loading-bar 2.5s infinite linear;
        }

        .group\/cinema:hover .animate-float {
            animation-duration: 5s;
        }
    </style>
    <div class="relative py-12">
        <div class="container mx-auto px-4 relative z-10">

            {{-- Mobile Filter Drawer --}}
            <div id="mobileFilterDrawer"
                class="fixed inset-y-0 left-0 z-[111] w-72 bg-[#0B0F1A]/95 backdrop-blur-xl border-r border-white/10 shadow-2xl transform -translate-x-full transition-transform duration-300 lg:hidden overflow-y-auto">
                {{-- ... (Drawer content same as before) ... --}}
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-white text-lg font-heading">{{ __('Filters') }}</h3>
                        <button class="text-text-secondary hover:text-white cursor-pointer"
                            onclick="$('#mobileFilterDrawer').addClass('-translate-x-full'); $('#mobileDrawerBackdrop').addClass('hidden');">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Mobile Sort --}}
                    <div class="mb-8">
                        <label
                            class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Sort By') }}</label>
                        <div class="relative group/sort">
                            <select id="sortPlansMobile"
                                class="sort-plans w-full appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-3 pr-10 text-sm text-white focus:border-accent-primary focus:ring-0 cursor-pointer outline-none transition-all hover:bg-white/10">
                                <option value="featured" class="bg-[#0B0F1A] text-white">{{ __('Featured') }}</option>
                                <option value="min_asc" class="bg-[#0B0F1A] text-white">
                                    {{ __('Min Investment (Low to High)') }}
                                </option>
                                <option value="min_desc" class="bg-[#0B0F1A] text-white">
                                    {{ __('Min Investment (High to Low)') }}</option>
                                <option value="roi_desc" class="bg-[#0B0F1A] text-white">{{ __('Highest ROI') }}</option>
                                <option value="duration_asc" class="bg-[#0B0F1A] text-white">{{ __('Shortest Duration') }}
                                </option>
                            </select>
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Copied Filters --}}
                    <div class="mb-8">
                        <label
                            class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Risk Profile') }}</label>
                        <div class="space-y-3">
                            @php
                                $risks = $investment_plans->pluck('risk_profile')->unique();
                            @endphp
                            @foreach ($risks as $risk)
                                <label
                                    class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition-colors">
                                    <div class="relative flex items-center">
                                        <input type="checkbox"
                                            class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-risk"
                                            value="{{ $risk }}">
                                    </div>
                                    <span
                                        class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize font-medium">{{ __($risk) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-8">
                        <label
                            class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Return Interval') }}</label>
                        <div class="space-y-3">
                            @php
                                $intervals = $investment_plans->pluck('return_interval')->unique();
                            @endphp
                            @foreach ($intervals as $interval)
                                <label
                                    class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition-colors">
                                    <div class="relative flex items-center">
                                        <input type="checkbox"
                                            class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-interval"
                                            value="{{ $interval }}">
                                    </div>
                                    <span
                                        class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize font-medium">{{ __($interval) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-8">
                        <label
                            class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Interests') }}</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $allInterests = $investment_plans->pluck('interests')->flatten()->unique();
                            @endphp
                            @foreach ($allInterests as $interest)
                                <button
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold border border-white/10 bg-white/5 text-text-secondary hover:bg-white/10 hover:text-white hover:border-white/20 transition-all filter-interest cursor-pointer"
                                    data-value="{{ $interest }}">
                                    {{ __($interest) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-auto pt-6 border-t border-white/10">
                        <button
                            class="btn-reset-filters w-full py-3 rounded-xl text-sm font-bold text-text-secondary hover:text-white hover:bg-white/5 transition-colors cursor-pointer border border-white/10 hover:border-white/20">
                            {{ __('Reset Filters') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Overlay --}}
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[50] hidden lg:hidden" id="mobileDrawerBackdrop"
                onclick="$('#mobileFilterDrawer').addClass('-translate-x-full'); $(this).addClass('hidden');"></div>

            {{-- Avant-Garde Glass Prism Showcase --}}
            @if ($recommended_plans->isNotEmpty())
                <div
                    class="relative w-full overflow-hidden rounded-[2.5rem] bg-[#030303] border border-white/5 shadow-2xl shadow-black/50 mb-24 min-h-[500px] flex items-center relative group/cinema">

                    {{-- Ambient Aurora Background --}}
                    <div class="absolute inset-0 overflow-hidden pointer-events-none">
                        <div
                            class="absolute -top-[50%] -left-[20%] w-[100%] h-[150%] bg-accent-primary/20 blur-[120px] rounded-full mix-blend-screen animate-pulse-slow">
                        </div>
                        <div
                            class="absolute top-[20%] -right-[20%] w-[80%] h-[120%] bg-accent-secondary/10 blur-[120px] rounded-full mix-blend-screen animate-float">
                        </div>
                        <div
                            class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-20 mix-blend-overlay">
                        </div>
                    </div>

                    {{-- HUD Grid Overlay --}}
                    <div
                        class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_at_center,black_40%,transparent_70%)] pointer-events-none">
                    </div>

                    <div class="relative z-10 w-full p-8 md:p-12">

                        {{-- Cinematic Header --}}
                        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                            <div>
                                <span
                                    class="inline-block px-3 py-1 rounded-full border border-accent-primary/50 bg-accent-primary/10 text-accent-primary text-[10px] font-mono tracking-widest uppercase mb-4 backdrop-blur-md">
                                    // {{ __('PREMIER_SELECTIONS_V.01') }}
                                </span>
                                <h2
                                    class="text-4xl md:text-5xl font-heading font-bold text-white leading-none tracking-tight">
                                    {{ __('Featured') }} <br />
                                    <span
                                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary via-white to-accent-secondary">{{ __('Opportunities') }}</span>
                                </h2>
                            </div>
                            <div class="hidden md:block text-right">
                                <div class="text-text-secondary text-xs font-mono mb-1">{{ __('SYSTEM_STATUS:') }} <span
                                        class="text-emerald-400">{{ __('OPTIMAL') }}</span></div>
                                <div class="flex gap-1">
                                    <span class="w-1 h-1 bg-accent-primary rounded-full animate-ping"></span>
                                    <span class="w-12 h-1 bg-white/10 rounded-full overflow-hidden relative">
                                        <div class="absolute inset-0 bg-accent-primary/50 w-2/3 h-full animate-loading-bar">
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Prism Cards Gallery --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($recommended_plans as $plan)
                                <div class="group relative rounded-[2rem] bg-white/[0.03] backdrop-blur-xl border border-white/10 overflow-hidden transition-all duration-500 hover:scale-[1.02] hover:bg-white/[0.06] hover:border-accent-primary/30 hover:shadow-[0_0_30px_rgba(139,92,246,0.15)] flex flex-col justify-between min-h-[380px]"
                                    data-min="{{ $plan->min_investment }}" data-roi="{{ $plan->return_percent }}"
                                    data-duration="{{ (int) $plan->duration }}" data-risk="{{ $plan->risk_profile }}"
                                    data-interval="{{ $plan->return_interval }}"
                                    data-interests='{{ json_encode($plan->interests ?? []) }}' data-featured="1">

                                    {{-- Card Glow --}}
                                    <div
                                        class="absolute top-0 right-0 w-32 h-32 bg-accent-primary/20 blur-[60px] rounded-full group-hover:bg-accent-primary/30 transition-all duration-500">
                                    </div>

                                    <div class="p-8 relative z-10">
                                        <div class="flex justify-between items-start mb-8">
                                            <div
                                                class="text-xs font-mono text-accent-primary/80 border border-accent-primary/30 px-2 py-1 rounded">
                                                ID:{{ $plan->id }}
                                            </div>
                                            @if ($plan->capital_returned)
                                                <div
                                                    class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)] animate-pulse">
                                                </div>
                                            @endif
                                        </div>

                                        <h3
                                            class="text-2xl font-heading font-bold text-white mb-2 leading-tight group-hover:text-accent-primary transition-colors duration-300">
                                            {{ __($plan->name) }}</h3>
                                        <p class="text-text-secondary text-sm line-clamp-2 mb-6 opacity-80">
                                            {{ __($plan->description) }}</p>

                                        <div class="space-y-4">
                                            <div>
                                                <span
                                                    class="block text-[10px] uppercase tracking-widest text-text-secondary mb-1">
                                                    {{ __('Projected ROI') }}</span>
                                                <div
                                                    class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-white/20 tracking-tighter group-hover:from-accent-primary group-hover:to-accent-secondary transition-all duration-500">
                                                    {{ $plan->return_percent }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Glass Footer --}}
                                    <div
                                        class="mt-auto bg-black/20 border-t border-white/5 p-6 backdrop-blur-md flex items-center justify-between group-hover:bg-accent-primary/10 transition-colors duration-300">
                                        <div>
                                            <span
                                                class="block text-[10px] uppercase tracking-widest text-text-secondary/70">{{ __('Entry') }}</span>
                                            <span
                                                class="text-lg font-bold text-white">{{ getSetting('currency_symbol', '$') }}{{ number_format($plan->min_investment) }}</span>
                                        </div>
                                        <a href="{{ route('user.login') }}"
                                            class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-accent-primary group-hover:border-accent-primary group-hover:text-white transition-all duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="5" y1="12" x2="19" y2="12">
                                                </line>
                                                <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Content: Sidebar Filters + Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Filters Sidebar (Desktop) --}}
                <div class="hidden lg:block space-y-6">
                    <div
                        class="bg-white/[0.02] backdrop-blur-md border border-white/5 rounded-3xl p-6 sticky top-24 shadow-2xl shadow-black/20">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="text-text-secondary">
                                    <line x1="4" y1="21" x2="4" y2="14" />
                                    <line x1="4" y1="10" x2="4" y2="3" />
                                    <line x1="12" y1="21" x2="12" y2="12" />
                                    <line x1="12" y1="8" x2="12" y2="3" />
                                    <line x1="20" y1="21" x2="20" y2="16" />
                                    <line x1="20" y1="12" x2="20" y2="3" />
                                    <line x1="1" y1="14" x2="7" y2="14" />
                                    <line x1="9" y1="8" x2="15" y2="8" />
                                    <line x1="17" y1="16" x2="23" y2="16" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-white font-heading">{{ __('Filter & Sort') }}</h3>
                        </div>

                        {{-- Sort --}}
                        <div class="mb-6">
                            <label
                                class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Sort By') }}</label>
                            <div class="relative group/sort">
                                <select id="sortPlansDesktop"
                                    class="sort-plans w-full appearance-none bg-white/5 border border-white/10 rounded-xl px-4 py-3 pr-10 text-sm text-white focus:border-accent-primary focus:ring-0 cursor-pointer outline-none hover:bg-white/10 transition-colors">
                                    <option value="featured" class="bg-[#0B0F1A] text-white">{{ __('Featured') }}
                                    </option>
                                    <option value="min_asc" class="bg-[#0B0F1A] text-white">
                                        {{ __('Min Investment (Low to High)') }}</option>
                                    <option value="min_desc" class="bg-[#0B0F1A] text-white">
                                        {{ __('Min Investment (High to Low)') }}</option>
                                    <option value="roi_desc" class="bg-[#0B0F1A] text-white">{{ __('Highest ROI') }}
                                    </option>
                                    <option value="duration_asc" class="bg-[#0B0F1A] text-white">
                                        {{ __('Shortest Duration') }}
                                    </option>
                                </select>
                                <div
                                    class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-text-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m6 9 6 6 6-6" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {{-- Categories / Risk --}}
                        <div class="mb-6">
                            <label
                                class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Risk Profile') }}</label>
                            <div class="space-y-2">
                                @php
                                    $risks = $investment_plans->pluck('risk_profile')->unique();
                                @endphp
                                @foreach ($risks as $risk)
                                    <label
                                        class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition-colors">
                                        <div class="relative flex items-center">
                                            <input type="checkbox"
                                                class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-risk"
                                                value="{{ $risk }}">
                                        </div>
                                        <span
                                            class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize font-medium">{{ __($risk) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-6">
                            <label
                                class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Return Interval') }}</label>
                            <div class="space-y-2">
                                @php
                                    $intervals = $investment_plans->pluck('return_interval')->unique();
                                @endphp
                                @foreach ($intervals as $interval)
                                    <label
                                        class="flex items-center gap-3 cursor-pointer group p-2 rounded-lg hover:bg-white/5 transition-colors">
                                        <div class="relative flex items-center">
                                            <input type="checkbox"
                                                class="peer h-4 w-4 rounded border-white/20 bg-white/5 text-accent-primary focus:ring-accent-primary/50 transition-all filter-interval"
                                                value="{{ $interval }}">
                                        </div>
                                        <span
                                            class="text-sm text-text-secondary group-hover:text-white transition-colors capitalize font-medium">{{ __($interval) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Interests --}}
                        <div class="mb-8">
                            <label
                                class="text-xs text-text-secondary uppercase tracking-wider font-bold mb-3 block pl-1">{{ __('Interests') }}</label>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $allInterests = $investment_plans->pluck('interests')->flatten()->unique();
                                @endphp
                                @foreach ($allInterests as $interest)
                                    <button
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold border border-white/10 bg-white/5 text-text-secondary hover:bg-white/10 hover:text-white hover:border-white/20 transition-all filter-interest cursor-pointer"
                                        data-value="{{ $interest }}">
                                        {{ __($interest) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <button id="resetFilters"
                            class="btn-reset-filters w-full py-3 rounded-xl text-xs font-bold text-text-secondary hover:text-white hover:bg-white/5 transition-colors cursor-pointer border border-white/10 hover:border-white/20">
                            {{ __('Reset Filters') }}
                        </button>
                    </div>
                </div>

                {{-- Plans Grid --}}
                <div class="lg:col-span-3 space-y-6">

                    {{-- Toggle Mobile Filters --}}
                    <div class="flex items-center justify-between mb-8">
                        <h2 id="exploreMarketplace" class="text-3xl font-bold text-white font-heading">
                            {{ __('All Opportunities') }}</h2>
                        <button
                            class="lg:hidden px-4 py-2.5 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 text-white font-medium flex items-center gap-2 cursor-pointer hover:bg-white/10 transition-colors"
                            onclick="$('#mobileFilterDrawer').removeClass('-translate-x-full'); $('#mobileDrawerBackdrop').removeClass('hidden');">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="4" y1="21" x2="4" y2="14" />
                                <line x1="4" y1="10" x2="4" y2="3" />
                                <line x1="12" y1="21" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12" y2="3" />
                                <line x1="20" y1="21" x2="20" y2="16" />
                                <line x1="20" y1="12" x2="20" y2="3" />
                                <line x1="1" y1="14" x2="7" y2="14" />
                                <line x1="9" y1="8" x2="15" y2="8" />
                                <line x1="17" y1="16" x2="23" y2="16" />
                            </svg>
                            {{ __('Sort & Filter') }}
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6" id="plansContainer">
                        @foreach ($investment_plans as $plan)
                            <div class="plan-card group relative bg-white/[0.02] backdrop-blur-md border border-white/5 rounded-3xl p-1 transition-all duration-300 hover:border-accent-primary/30 hover:bg-white/[0.04] hover:-translate-y-2 hover:shadow-2xl hover:shadow-accent-primary/10 flex flex-col"
                                data-min="{{ $plan->min_investment }}" data-roi="{{ $plan->return_percent }}"
                                data-duration="{{ (int) $plan->duration }}" data-risk="{{ $plan->risk_profile }}"
                                data-interval="{{ $plan->return_interval }}"
                                data-interests='{{ json_encode($plan->interests ?? []) }}'
                                data-featured="{{ $plan->is_featured ? 1 : 0 }}">

                                {{-- Inner Content --}}
                                <div
                                    class="relative bg-[#0B0F1A]/40 rounded-[22px] p-6 h-full flex flex-col overflow-hidden">
                                    {{-- Glow Effect --}}
                                    <div
                                        class="absolute -top-20 -right-20 w-40 h-40 bg-accent-primary/10 rounded-full blur-3xl group-hover:bg-accent-primary/20 transition-all duration-500">
                                    </div>

                                    {{-- Header --}}
                                    <div class="flex justify-between items-start mb-6 relative">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 p-3 flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shadow-inner">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                                    class="text-white group-hover:text-accent-primary transition-colors">
                                                    <path d="M12 2v20M2 12h20M12 2l-5 5 5-5 5 5" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="font-heading font-bold text-lg text-white leading-tight mb-1 group-hover:text-accent-primary transition-colors">
                                                    {{ __($plan->name) }}
                                                </h3>
                                                <span
                                                    class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 border border-white/5 text-text-secondary uppercase tracking-wider group-hover:bg-white/10 transition-colors">
                                                    {{ __(str_replace('_', ' ', $plan->risk_profile)) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-1.5">
                                            @if ($plan->is_featured && $recommended_plans->contains('id', $plan->id))
                                                <span
                                                    class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-accent-primary text-white shadow-lg shadow-accent-primary/20 uppercase tracking-wide">
                                                    {{ __('Recommended') }}
                                                </span>
                                            @endif
                                            @if ($plan->capital_returned)
                                                <span
                                                    class="text-[10px] text-emerald-400 flex items-center gap-1.5 font-bold bg-emerald-500/10 px-2 py-0.5 rounded-md border border-emerald-500/10">
                                                    <span class="relative flex h-1.5 w-1.5">
                                                        <span
                                                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                        <span
                                                            class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-400"></span>
                                                    </span>
                                                    {{ __('Capital Returned') }}
                                                </span>
                                            @else
                                                <span
                                                    class="text-[10px] text-text-secondary font-bold px-2 py-0.5 rounded-md bg-white/5 border border-white/5">{{ __('Capital Included') }}</span>
                                            @endif
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold {{ $plan->compounding ? 'bg-blue-500/10 text-blue-400 border-blue-500/10' : 'bg-white/5 text-text-secondary border-white/5' }} border uppercase tracking-wide">
                                                {{ $plan->compounding ? __('Compounding') : __('Simple Interest') }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Description --}}
                                    <p class="text-text-secondary text-sm leading-relaxed mb-6 line-clamp-2"
                                        title="{{ __($plan->description) }}">
                                        {{ __($plan->description) }}
                                    </p>

                                    {{-- Tags --}}
                                    @if (!empty($plan->interests))
                                        <div class="flex flex-wrap gap-2 mb-6 pointer-events-none opacity-80">
                                            @foreach ($plan->interests as $tag)
                                                <span
                                                    class="px-2 py-1 rounded-md bg-white/5 border border-white/5 text-[10px] font-bold text-text-secondary uppercase tracking-wider transition-colors">
                                                    {{ __($tag) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Metrics --}}
                                    <div
                                        class="grid grid-cols-3 gap-2 py-4 border-t border-b border-white/5 mb-6 bg-white/[0.02] rounded-2xl px-2">
                                        <div class="text-center p-2 rounded-lg hover:bg-white/5 transition-colors">
                                            <span
                                                class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1 opacity-70">{{ __('ROI') }}</span>
                                            <span
                                                class="block text-xl font-bold text-accent-primary tracking-tight">{{ $plan->return_percent }}%</span>
                                        </div>
                                        <div
                                            class="text-center border-l border-white/5 p-2 rounded-lg hover:bg-white/5 transition-colors">
                                            <span
                                                class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1 opacity-70">{{ __('Term') }}</span>
                                            <span class="block text-xl font-bold text-white tracking-tight">
                                                {{ $plan->duration }}<span
                                                    class="text-xxs ml-0.5 align-top opacity-50">{{ substr(__($plan->duration_type), 0, 1) }}</span>
                                            </span>
                                        </div>
                                        <div
                                            class="text-center border-l border-white/5 p-2 rounded-lg hover:bg-white/5 transition-colors">
                                            <span
                                                class="block text-[10px] text-text-secondary uppercase tracking-wider font-bold mb-1 opacity-70">{{ __('Min') }}</span>
                                            <span
                                                class="block text-lg font-bold text-white tracking-tight">{{ getSetting('currency_symbol', '$') }}{{ number_format($plan->min_investment, 0) }}</span>
                                        </div>
                                    </div>

                                    {{-- Action --}}
                                    <div class="mt-auto">
                                        <a href="{{ route('user.login') }}"
                                            class="invest-btn w-full py-3.5 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 hover:border-white/20 text-white font-bold transition-all shadow-none hover:shadow-lg hover:shadow-white/5 active:scale-[0.98] cursor-pointer flex items-center justify-center gap-2 group/btn relative overflow-hidden">
                                            <span class="relative z-10">{{ __('Start Investing') }}</span>
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4 transition-transform group-hover/btn:translate-x-1 relative z-10"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12h14"></path>
                                                <path d="m12 5 7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Empty State --}}
                    <div id="noPlansFound" class="hidden text-center py-20">
                        <div
                            class="inline-flex p-6 rounded-full bg-white/5 mb-6 animate-float-slow backdrop-blur-md border border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="text-text-secondary">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ __('No plans match your filters') }}</h3>
                        <p class="text-text-secondary mb-6">
                            {{ __('Try adjusting your criteria to find more opportunities.') }}</p>
                        <button onclick="$('#resetFilters').click()"
                            class="px-6 py-2 rounded-lg bg-accent-primary/10 text-accent-primary border border-accent-primary/20 hover:bg-accent-primary hover:text-white transition-all">{{ __('Clear all filters') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // --- FILTERING & SORTING LOGIC ---

            // State
            let activeRiskFilters = [];
            let activeInterestFilters = [];
            let activeIntervalFilters = [];

            // Sort Handler (Desktop & Mobile)
            $('.sort-plans').on('change', function() {
                const sortType = $(this).val();

                // Sync values
                $('.sort-plans').val(sortType);

                const container = $('#plansContainer');
                const cards = container.children('.plan-card').get();

                cards.sort(function(a, b) {
                    const $a = $(a);
                    const $b = $(b);

                    if (sortType === 'min_asc') return $a.data('min') - $b.data('min');
                    if (sortType === 'min_desc') return $b.data('min') - $a.data('min');
                    if (sortType === 'roi_desc') return $b.data('roi') - $a.data('roi');
                    if (sortType === 'duration_asc') return $a.data('duration') - $b.data(
                        'duration');
                    // Default/Featured
                    return $b.data('featured') - $a.data('featured');
                });

                $.each(cards, function(idx, card) {
                    container.append(card);
                });
            });

            // Filter Handlers
            $('.filter-risk').on('change', function() {
                // Determine active risks from ALL checked boxes (desktop + mobile)
                // Filter unique values
                const allChecked = $('.filter-risk:checked').map(function() {
                    return $(this).val();
                }).get();
                activeRiskFilters = [...new Set(allChecked)];

                // Sync checkboxes
                const val = $(this).val();
                const isChecked = $(this).is(':checked');
                $(`.filter-risk[value="${val}"]`).prop('checked', isChecked);

                applyFilters();
            });

            $('.filter-interest').on('click', function() {
                const val = $(this).data('value');
                const isAdding = !activeInterestFilters.includes(val);
                const $buttons = $(`.filter-interest[data-value="${val}"]`);

                if (!isAdding) {
                    activeInterestFilters = activeInterestFilters.filter(i => i !== val);
                    $buttons.removeClass(
                        'bg-accent-primary text-white border-accent-primary active-filter pl-2 pr-2');
                    $buttons.find('.remove-icon').remove();
                } else {
                    activeInterestFilters.push(val);
                    $buttons.addClass(
                        'bg-accent-primary text-white border-accent-primary active-filter flex items-center gap-1 pl-2 pr-1'
                    );
                    // Avoid double icon append
                    $buttons.each(function() {
                        if ($(this).find('.remove-icon').length === 0) {
                            $(this).append(
                                `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="remove-icon text-red-500 bg-white/10 rounded-full p-0.5 ml-1 hover:bg-white/20 transition-colors"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`
                            );
                        }
                    });
                }
                applyFilters();
            });

            $('.filter-interval').on('change', function() {
                const allChecked = $('.filter-interval:checked').map(function() {
                    return $(this).val();
                }).get();
                activeIntervalFilters = [...new Set(allChecked)];

                // Sync checkboxes
                const val = $(this).val();
                const isChecked = $(this).is(':checked');
                $(`.filter-interval[value="${val}"]`).prop('checked', isChecked);

                applyFilters();
            });

            // Reset Handler
            $('.btn-reset-filters, #resetFilters').on('click', function() {
                $('.filter-risk').prop('checked', false);
                $('.filter-interval').prop('checked', false);
                $('.filter-interest').removeClass(
                    'bg-accent-primary text-white border-accent-primary active-filter flex items-center gap-1 pl-2 pr-1 pl-2 pr-2'
                );
                $('.filter-interest .remove-icon').remove();
                activeRiskFilters = [];
                activeInterestFilters = [];
                activeIntervalFilters = [];
                $('.sort-plans').val('featured').trigger('change');
                applyFilters();
            });

            function applyFilters() {
                let visibleCount = 0;
                $('#plansContainer .plan-card').each(function() {
                    const $card = $(this);
                    const cardRisk = $card.data('risk');
                    const cardInterval = $card.data('interval');
                    const cardInterests = $card.data('interests'); // array

                    // Risk Check
                    const passRisk = activeRiskFilters.length === 0 || activeRiskFilters.includes(cardRisk);

                    // Interval Check
                    const passInterval = activeIntervalFilters.length === 0 || activeIntervalFilters
                        .includes(cardInterval);

                    // Interest Check (OR logic: match any selected interest)
                    const passInterest = activeInterestFilters.length === 0 || activeInterestFilters.some(
                        i => cardInterests.includes(i));

                    if (passRisk && passInterval && passInterest) {
                        $card.show();
                        visibleCount++;
                    } else {
                        $card.hide();
                    }
                });

                if (visibleCount === 0) $('#noPlansFound').removeClass('hidden');
                else $('#noPlansFound').addClass('hidden');
            }

            // Handle sector query parameter
            const urlParams = new URLSearchParams(window.location.search);
            const sector = urlParams.get('sector');
            if (sector) {
                const $targetButton = $(`.filter-interest[data-value="${sector}"]`);
                if ($targetButton.length > 0) {
                    // Apply filter
                    $targetButton.first().click();

                    // Scroll to marketplace
                    setTimeout(() => {
                        const element = document.getElementById('plansContainer');
                        if (element) {
                            element.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }, 100);
                }
            }
        });
    </script>
@endpush
