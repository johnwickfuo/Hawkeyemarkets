@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8">
        {{-- Header & Hero Stats --}}
        <div class="relative overflow-hidden rounded-2xl bg-secondary border border-white/5 p-6 md:p-8">
            <div
                class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-accent-primary/5 blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 -ml-16 -mb-16 w-40 h-40 rounded-full bg-purple-500/5 blur-2xl pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6">
                <div class="flex items-start gap-4 md:gap-5">
                    <div
                        class="p-2 md:p-3 bg-white/5 rounded-xl md:rounded-2xl border border-white/10 hidden md:flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-accent-primary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-3xl font-semibold md:font-bold text-white tracking-tight">
                            {{ __('Investment Portfolio') }}
                        </h1>
                        <p class="text-text-secondary text-xs md:text-base mt-1 md:mt-2 max-w-xl leading-relaxed">
                            {{ __('Monitor your asset performance, track earnings, and manage your investment strategy.') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full md:w-auto">
                    <a href="{{ route('user.investments.new') }}"
                        class="group relative w-full md:w-auto inline-flex items-center justify-center gap-2 md:gap-3 px-6 py-3 md:px-8 md:py-4 bg-accent-primary hover:bg-accent-primary-hover text-white font-semibold md:font-bold rounded-lg md:rounded-xl transition-all shadow-[0_4px_20px_rgba(var(--color-accent-primary),0.2)] md:shadow-[0_4px_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_6px_25px_rgba(var(--color-accent-primary),0.4)] active:scale-[0.98] overflow-hidden text-sm md:text-base">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000">
                        </div>
                        <svg class="w-4 h-4 md:w-5 md:h-5 transition-transform group-hover:bg-accent-primary" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>{{ __('New Investment') }}</span>
                    </a>
                </div>
            </div>

            {{-- Hero Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                {{-- Active Capital --}}
                <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                    <p class="text-text-secondary text-sm mb-1">{{ __('Active Capital') }}</p>
                    <h3 class="text-2xl font-bold text-white">
                        {{ number_format($analytics['active_capital'], getSetting('decimal_places', 2)) }} <span
                            class="text-sm font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                    <div class="mt-2 text-xs text-text-secondary">
                        {{ $analytics['active_investments'] }} {{ __('Active Plans') }}
                    </div>
                </div>

                {{-- Total ROI --}}
                <div class="bg-white/5 rounded-xl p-4 border border-white/5 relative overflow-hidden">
                    <div
                        class="absolute right-0 top-0 p-3 opacity-10 text-emerald-500 transform translate-x-1/4 -translate-y-1/4">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-text-secondary text-sm mb-1">{{ __('Total Returns') }}</p>
                    <h3 class="text-2xl font-bold text-white">
                        {{ number_format($analytics['total_roi_earned'], getSetting('decimal_places', 2)) }} <span
                            class="text-sm font-normal text-text-secondary">{{ getSetting('currency') }}</span>
                    </h3>
                    <div class="mt-2 text-xs text-emerald-400 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        {{ __('Lifetime Earnings') }}
                    </div>
                </div>

                {{-- Next Payout --}}
                <div class="bg-white/5 rounded-xl p-4 border border-white/5">
                    <p class="text-text-secondary text-sm mb-1">{{ __('Next Payout') }}</p>
                    @if ($analytics['next_roi_soonest'])
                        <h3 class="text-xl font-bold text-white" id="next-payout-timer">
                            --:--:--
                        </h3>
                        <div class="mt-2 text-xs text-text-secondary">
                            {{ \Carbon\Carbon::createFromTimestamp($analytics['next_roi_soonest'])->diffForHumans() }}
                        </div>
                    @else
                        <h3 class="text-xl font-bold text-text-secondary">{{ __('No Active Cycles') }}</h3>
                        <div class="mt-2 text-xs text-text-secondary">
                            {{ __('Start an investment to earn') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Analytics Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Earnings Trend --}}
            <div class="lg:col-span-2 bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                {{-- Background SVG --}}
                <div class="absolute -right-24 -bottom-24 opacity-5 rotate-12 pointer-events-none">
                    <svg class="w-96 h-96 text-accent-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="0.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-white font-bold flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z">
                                </path>
                            </svg>
                            {{ __('Earnings Performance') }}
                        </h3>
                        <div class="flex items-center gap-2 text-sm">
                            <span
                                class="px-3 py-1 bg-white/5 rounded-lg text-text-secondary">{{ __('Last 14 Days') }}</span>
                        </div>
                    </div>
                    <div id="earningsChart" class="w-full h-[300px]"></div>
                </div>
            </div>

            {{-- Asset Allocation --}}
            <div class="bg-secondary border border-white/5 rounded-2xl p-6 flex flex-col">
                <h3 class="text-white font-bold mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    {{ __('Asset Allocation') }}
                </h3>
                <div class="flex-1 flex items-center justify-center relative">
                    <div id="allocationChart" class="w-full h-[250px]"></div>
                    @if ($analytics['active_investments'] == 0)
                        <div
                            class="absolute inset-0 flex flex-col items-center justify-center bg-secondary/50 backdrop-blur-sm z-10">
                            <p class="text-text-secondary text-sm">{{ __('No active assets') }}</p>
                        </div>
                    @endif
                </div>
                <div class="mt-4 space-y-2">
                    @foreach ($analytics['earnings_by_interest'] as $index => $interest)
                        <div
                            class="flex items-center justify-between text-sm {{ $index >= 3 ? 'hidden more-assets' : '' }}">
                            <span class="text-text-secondary flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-accent-primary"></span>
                                {{ __($interest->interest) }}
                            </span>
                            <span class="text-white font-medium"><span
                                    class="text-xs">{{ getSetting('currency_symbol', getSetting('currency')) }}</span>{{ number_format($interest->total, getSetting('decimal_places', 2)) }}</span>
                        </div>
                    @endforeach

                    @if ($analytics['earnings_by_interest']->count() > 3)
                        <button
                            onclick="document.querySelectorAll('.more-assets').forEach(el => el.classList.toggle('hidden')); this.innerText = this.innerText === '{{ __('Show More') }}' ? '{{ __('Show Less') }}' : '{{ __('Show More') }}'"
                            class="text-xs text-accent-primary hover:text-accent-primary-hover font-medium flex items-center gap-1 transition-colors w-full justify-center pt-2 cursor-pointer">
                            <span>{{ __('Show More') }}</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Active Investments --}}
        <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Active Investments') }}
                </h3>
                <span
                    class="px-3 py-1 bg-emerald-500/10 text-emerald-500 text-xs font-bold rounded-full border border-emerald-500/20">
                    {{ $analytics['active_investments'] }} {{ __('Running') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Plan') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Invested') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('ROI') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Progress') }}</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Next Payout') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($my_investments->where('status', 'active') as $investment)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-white font-bold">{{ __($investment->plan->name) }}</p>
                                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1">
                                            <span class="text-emerald-400 font-bold text-xs">
                                                {{ 0 + $investment->plan->return_percent }}%
                                            </span>
                                            <span class="text-text-secondary text-xs flex items-center gap-1">
                                                <span>{{ __('for') }}</span>
                                                <span class="text-white">{{ $investment->plan->duration }}
                                                    {{ __($investment->plan->duration_type) }}</span>
                                            </span>
                                            <span
                                                class="px-1.5 py-0.5 rounded-md text-[10px] font-medium bg-white/5 border border-white/10 text-text-secondary uppercase tracking-wider">
                                                {{ __($investment->plan->return_interval) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-white">
                                        {{ number_format($investment->capital_invested, getSetting('decimal_places', 2)) }}
                                        <span class="text-xs text-text-secondary">{{ getSetting('currency') }}</span>
                                    </p>
                                    @if ($investment->plan->compounding)
                                        <span
                                            class="text-[10px] text-accent-primary bg-accent-primary/10 px-1.5 py-0.5 rounded">{{ __('Compounding') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-emerald-500 font-medium">
                                        <span
                                            class="text-xs">+</span>{{ number_format($investment->roi_earned, getSetting('decimal_places', 2)) }}
                                    </p>
                                    <p class="text-xs text-text-secondary">
                                        {{ number_format(($investment->roi_earned / $investment->capital_invested) * 100, 2) }}%
                                    </p>
                                </td>
                                <td class="px-6 py-4 w-1/4">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-text-secondary">{{ $investment->cycle_count }} /
                                            {{ $investment->total_cycles }}</span>
                                        <span
                                            class="text-white">{{ number_format(($investment->cycle_count / $investment->total_cycles) * 100, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-white/10 rounded-full h-1.5">
                                        <div class="bg-gradient-to-r from-accent-primary to-purple-500 h-1.5 rounded-full transition-all duration-500"
                                            style="width: {{ ($investment->cycle_count / $investment->total_cycles) * 100 }}%">
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if ($investment->next_roi_at)
                                        <p class="text-white text-sm">
                                            {{ \Carbon\Carbon::createFromTimestamp($investment->next_roi_at)->format('d M H:i') }}
                                        </p>
                                        <p class="text-xs text-text-secondary">
                                            {{ \Carbon\Carbon::createFromTimestamp($investment->next_roi_at)->diffForHumans() }}
                                        </p>
                                    @else
                                        <span class="text-text-secondary">--</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-secondary">
                                    {{ __('No active investments found.') }}
                                    <a href="{{ route('user.investments.new') }}"
                                        class="text-accent-primary hover:underline ml-1">{{ __('Explore Plans') }}</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Earnings --}}
        <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-white/5 flex items-center justify-between">

                <div class="lg:col-span-1 flex flex-col justify-center">
                    <h2 class="text-lg font-bold text-white font-heading tracking-tight flex items-center gap-3">
                        <span
                            class="w-2 h-8 bg-accent-primary rounded-sm shadow-[0_0_15px_rgba(var(--color-accent-primary),0.5)]"></span>
                        {{ __('Recent Earnings') }}
                    </h2>

                </div>

                <a href="{{ route('user.investments.earnings') }}"
                    class="text-sm text-accent-primary hover:text-accent-primary-hover font-medium flex items-center gap-1 transition-colors">
                    {{ __('View History') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3">
                        </path>
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5">
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Date') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Plan / Source') }}</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Config') }}</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-bold text-text-secondary uppercase tracking-wider">
                                {{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($investment_earnings as $earning)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 text-sm text-text-secondary">
                                    {{ $earning->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-white font-medium">{{ $earning->investment->plan->name ?? __('Unknown Plan') }}</span>
                                    <div class="text-xs text-text-secondary">
                                        {{ __($earning->interest) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-xs font-medium text-text-secondary">
                                        {{ __($earning->risk_profile) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span
                                        class="text-emerald-500 font-bold">+{{ number_format($earning->amount, getSetting('decimal_places', 2)) }}</span>
                                    <span class="text-xs text-text-secondary">{{ getSetting('currency') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-text-secondary">
                                    {{ __('No earnings records found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Earnings Trend Chart
            const earningsData = @json($analytics['earnings_trend_14d']);
            const labels = earningsData.map(item => item.date);
            const values = earningsData.map(item => parseFloat(item.total));

            const earningsOptions = {
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit',
                    background: 'transparent',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                    }
                },
                series: [{
                    name: "{{ __('Earnings') }}",
                    data: values
                }],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '12', // Tiny fixed width bars
                        borderRadius: 6, // Fully rounded top
                        borderRadiusApplication: 'end',
                    },
                },
                xaxis: {
                    categories: labels,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#94a3b8'
                        }
                    },
                    tooltip: {
                        enabled: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#94a3b8'
                        },
                        formatter: (val) => val.toFixed({{ getSetting('decimal_places', 2) }})
                    }
                },
                grid: {
                    borderColor: 'rgba(255, 255, 255, 0.05)',
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 10
                    }
                },
                colors: ['#8b5cf6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#6366f1'], // Gradient to indigo
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: (val) => val + " {{ getSetting('currency') }}"
                    },
                    style: {
                        fontSize: '12px'
                    },
                    background: '#1e293b',
                    borderColor: '#334155',
                }
            };

            const earningsChart = new ApexCharts(document.querySelector("#earningsChart"), earningsOptions);
            earningsChart.render();


            // Asset Allocation Chart
            const allocationData = @json($analytics['earnings_by_interest']);
            const allocationLabels = allocationData.map(item => item.interest.replace(/_/g, ' ').replace(/\b\w/g,
                c => c.toUpperCase()));
            const allocationValues = allocationData.map(item => parseFloat(item.total));

            if (allocationValues.length > 0) {
                const allocationOptions = {
                    chart: {
                        type: 'donut',
                        height: 250,
                        background: 'transparent'
                    },
                    series: allocationValues,
                    labels: allocationLabels,
                    colors: ['#8b5cf6', '#a78bfa', '#c4b5fd', '#ddd6fe', '#6366f1', '#ec4899', '#10b981'],
                    legend: {
                        show: false
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: "{{ __('Total') }}",
                                        color: '#cbd5e1',
                                        formatter: function(w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                                .toFixed({{ getSetting('decimal_places', 2) }}) +
                                                " {{ getSetting('currency') }}";
                                        }
                                    },
                                    value: {
                                        color: '#ffffff',
                                        fontSize: '18px',
                                        fontWeight: 600
                                    }
                                }
                            }
                        }
                    },
                    stroke: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        theme: 'dark',
                        y: {
                            formatter: (val) => val + " {{ getSetting('currency') }}"
                        }
                    }
                };

                const allocationChart = new ApexCharts(document.querySelector("#allocationChart"),
                    allocationOptions);
                allocationChart.render();
            }


            // Next Payout Timer
            const nextRoiTimestamp = {{ $analytics['next_roi_soonest'] ?? 0 }};
            if (nextRoiTimestamp > 0) {
                const timerElement = document.getElementById('next-payout-timer');

                function updateTimer() {
                    const now = Math.floor(Date.now() / 1000);
                    const diff = nextRoiTimestamp - now;

                    if (diff <= 0) {
                        timerElement.innerHTML = "{{ __('Processing...') }}";
                        // Optionally reload page after a short delay
                        // setTimeout(() => location.reload(), 5000);
                        return;
                    }

                    const hours = Math.floor(diff / 3600);
                    const minutes = Math.floor((diff % 3600) / 60);
                    const seconds = diff % 60;

                    timerElement.innerHTML =
                        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }

                updateTimer();
                setInterval(updateTimer, 1000);
            }
        });
    </script>
@endsection
