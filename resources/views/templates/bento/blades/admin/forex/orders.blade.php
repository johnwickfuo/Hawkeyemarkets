@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="forex-orders-wrapper" class="space-y-8">

        {{-- Page Header & Mode Switcher --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}"
                    class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-text-secondary hover:text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight">{{ __('Forex Order History') }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span
                            class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest {{ ($mode ?? 'live') === 'live' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                            {{ strtoupper($mode ?? 'live') }} {{ __('MODE') }}
                        </span>
                        <p class="text-[10px] text-text-secondary font-bold uppercase tracking-widest opacity-50">
                            {{ __('Audit and review logs') }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center bg-white/[0.03] border border-white/[0.06] rounded-2xl p-1">
                    <a href="{{ request()->fullUrlWithQuery(['mode' => 'live']) }}"
                        class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ ($mode ?? 'live') === 'live' ? 'bg-accent-primary text-secondary shadow-lg' : 'text-slate-500 hover:text-white' }}">
                        {{ __('Live') }}
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['mode' => 'demo']) }}"
                        class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all {{ ($mode ?? 'live') === 'demo' ? 'bg-amber-500 text-secondary shadow-lg' : 'text-slate-500 hover:text-white' }}">
                        {{ __('Demo') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Analytics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00(2 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Orders') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_orders']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-amber-500/20 rounded-lg text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Pending') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['pending_count']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Filled') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['filled_count']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-red-500/20 rounded-lg text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Cancelled') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['cancelled_count']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div
                class="lg:col-span-2 relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div class="relative z-10 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                        <div>
                            <div class="text-sm font-bold text-white tracking-wide">{{ __('Execution Trend') }}</div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono">
                                {{ __('Order Flow Over Time') }}</p>
                        </div>
                        <div
                            class="flex items-center flex-wrap gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                            @foreach (['7d' => '7D', '30d' => '30D', '60d' => '60D', '90d' => '90D', '1y' => '1Y', 'ytd' => 'YTD'] as $key => $label)
                                <button data-period="{{ $key }}"
                                    class="graph-period-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase transition-all {{ $key === '7d' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300' }}">{{ $label }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="relative h-64"><canvas id="orderTrendChart"></canvas></div>
                </div>
            </div>

            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div class="relative z-10 p-6 h-full flex flex-col">
                    <div class="mb-5">
                        <div class="text-sm font-bold text-white tracking-wide">{{ __('Order Status Distribution') }}</div>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono">
                            {{ __('Total Volume by Execution Status') }}</p>
                    </div>
                    <div class="relative flex-1 flex items-center justify-center p-4"><canvas
                            id="statusDistributionChart"></canvas></div>
                </div>
            </div>
        </div>

        {{-- Loading Overlay --}}
        <div id="loading-spinner"
            class="fixed inset-0 z-[200] hidden items-center justify-center bg-slate-950/50 backdrop-blur-sm transition-all">
            <div class="flex flex-col items-center gap-4">
                <div class="relative w-16 h-16">
                    <div class="absolute inset-0 border-4 border-accent-primary/20 rounded-full"></div>
                    <div
                        class="absolute inset-0 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                </div>
                <span
                    class="text-xs font-bold text-white uppercase tracking-widest animate-pulse">{{ __('Loading...') }}</span>
            </div>
        </div>

        {{-- Table Section Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-accent-primary rounded-full"></div>
                <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Order History') }}</h3>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                <form id="filter-form" method="GET" action="{{ route('admin.forex-trading.orders.index') }}"
                    class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                    <input type="hidden" name="mode" value="{{ $mode ?? 'live' }}">
                    <div class="relative group" id="status-filter-dropdown">
                        <button type="button"
                            class="dropdown-btn h-12 bg-white/5 border border-white/10 rounded-2xl px-5 flex items-center gap-3 text-sm font-medium text-white hover:bg-white/10 transition-all outline-none min-w-[140px]">
                            <span
                                class="selected-label">{{ request('status') ? ucfirst(request('status')) : __('All Status') }}</span>
                            <svg class="dropdown-icon w-4 h-4 text-text-secondary transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <input type="hidden" name="status" id="status-input" value="{{ request('status', 'all') }}">
                        <div
                            class="dropdown-menu absolute right-0 top-full mt-2 w-48 bg-[#0F172A] border border-white/10 rounded-2xl shadow-2xl py-2 z-50 hidden overflow-hidden backdrop-blur-xl">
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="all">{{ __('All Status') }}</div>
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="pending">{{ __('Pending') }}</div>
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="filled">{{ __('Filled') }}</div>
                            <div class="dropdown-option px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white hover:bg-white/5 cursor-pointer transition-colors uppercase tracking-widest"
                                data-value="cancelled">{{ __('Cancelled') }}</div>
                        </div>
                    </div>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('Search symbol or user...') }}"
                            class="h-12 bg-white/5 border border-white/10 rounded-2xl px-5 pr-12 text-base font-medium text-white focus:border-accent-primary transition-all outline-none w-full sm:w-64">
                        <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-text-secondary group-hover:text-accent-primary"><svg
                                class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Orders Table --}}
        <div id="orders-wrapper">
            <div
                class="bg-secondary border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl transition-opacity duration-300">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">
                                    {{ __('Trader') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60">
                                    {{ __('Symbol / Type') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60 text-right">
                                    {{ __('Price / Amount') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60 text-right">
                                    {{ __('Status') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-text-secondary opacity-60 text-right">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($orders as $order)
                                <tr class="group hover:bg-white/[0.01] transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-accent-primary/20 text-accent-primary flex items-center justify-center font-black text-xs border border-accent-primary/20 shrink-0">
                                                {{ substr($order->user->username, 0, 2) }}</div>
                                            <div>
                                                <div class="text-sm font-bold text-white leading-tight block">
                                                    {{ $order->user->username }}</div>
                                                <div class="text-[10px] text-text-secondary font-mono opacity-50">
                                                    {{ $order->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="px-2 py-1 rounded bg-white/5 border border-white/10 text-[10px] font-black text-white uppercase tracking-tighter">
                                                {{ $order->symbol }}</div>
                                            <div class="flex items-center gap-1.5">
                                                <span
                                                    class="text-[9px] font-black {{ $order->side === 'Buy' ? 'text-emerald-400' : 'text-red-400' }} uppercase tracking-widest">{{ __($order->side) }}</span>
                                                <span class="text-[9px] font-bold text-text-secondary opacity-40">/</span>
                                                <span
                                                    class="text-[9px] font-bold text-text-secondary uppercase tracking-widest">{{ __($order->type) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right font-mono">
                                        <div class="text-xs font-bold text-white">{{ number_format($order->price, 5) }}
                                        </div>
                                        <div class="text-[10px] text-accent-primary font-black mt-0.5">
                                            {{ number_format($order->amount, 2) }} <span
                                                class="opacity-50 text-[9px] uppercase">{{ $currency }}</span></div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        @php
                                            $statuses = [
                                                'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                                'filled' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            ];
                                            $statusClass = $statuses[$order->status] ?? $statuses['pending'];
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClass }}">{{ __($order->status) }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($order->status === 'pending')
                                                <button
                                                    onclick="cancelOrder({{ $order->id }}, '{{ $order->symbol }}')"
                                                    class="p-2.5 rounded-xl bg-amber-500/10 text-amber-500 border border-amber-500/20 hover:bg-amber-500 hover:text-white transition-all shadow-lg"
                                                    title="{{ __('Cancel Order') }}"><svg class="w-4 h-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg></button>
                                            @endif
                                            <button onclick="deleteOrder({{ $order->id }}, '{{ $order->symbol }}')"
                                                class="p-2.5 rounded-xl bg-white/5 text-text-secondary border border-white/10 hover:border-red-500 hover:text-red-400 transition-all shadow-lg"
                                                title="{{ __('Delete Record') }}"><svg class="w-4 h-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <h5 class="text-white font-bold">{{ __('No Orders Found') }}</h5>
                                        <p class="text-text-secondary text-xs mt-1">
                                            {{ __('No trading orders matching your criteria.') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($orders->hasPages())
                    <div class="px-8 py-6 bg-white/[0.01] border-t border-white/5 ajax-pagination">
                        {{ $orders->links('templates.bento.blades.partials.pagination') }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            const GRAPH_DATA = @json($graph_data);
            const STATUS_DATA = @json($status_chart_data);

            Chart.defaults.color = 'rgba(148,163,184,0.7)';
            Chart.defaults.font.size = 10;

            let trendChart = null;
            let statusChart = null;

            function initCharts() {
                const trendCtx = document.getElementById('orderTrendChart')?.getContext('2d');
                if (trendCtx) {
                    trendChart = new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: [],
                            datasets: []
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(255,255,255,0.03)'
                                    }
                                }
                            }
                        }
                    });
                    updateTrendChart('7d');
                }

                const statusCtx = document.getElementById('statusDistributionChart')?.getContext('2d');
                if (statusCtx) {
                    statusChart = new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: STATUS_DATA.map(d => d.status),
                            datasets: [{
                                data: STATUS_DATA.map(d => d.count),
                                backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                                borderWidth: 0,
                                cutout: '75%'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                }
                            }
                        }
                    });
                }
            }

            function updateTrendChart(period) {
                if (!trendChart || !GRAPH_DATA[period]) return;
                const data = GRAPH_DATA[period];
                trendChart.data.labels = data.pending?.labels || [];
                trendChart.data.datasets = [{
                        label: 'Pending',
                        data: data.pending?.data || [],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    },
                    {
                        label: 'Filled',
                        data: data.filled?.data || [],
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    },
                    {
                        label: 'Cancelled',
                        data: data.cancelled?.data || [],
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }
                ];
                trendChart.update();
            }

            $('.graph-period-btn').on('click', function() {
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass('text-slate-500');
                $(this).addClass('bg-white/10 text-white').removeClass('text-slate-500');
                updateTrendChart($(this).data('period'));
            });

            initCharts();

            function loadOrders(query = '') {
                $('#loading-spinner').removeClass('hidden').addClass('flex');
                $('#forex-orders-wrapper').addClass('opacity-50 pointer-events-none');
                $.ajax({
                    url: '{{ route('admin.forex-trading.orders.index') }}' + query,
                    method: 'GET',
                    success: function(res) {
                        $('#forex-orders-wrapper').html($(res).find('#forex-orders-wrapper').html());
                        initCharts();
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                        $('#forex-orders-wrapper').removeClass('opacity-50 pointer-events-none');
                    }
                });
            }

            $(document).on('click', '.dropdown-btn', function(e) {
                e.stopPropagation();
                $('.dropdown-menu').not($(this).next('.dropdown-menu')).addClass('hidden');
                $(this).next('.dropdown-menu').toggleClass('hidden');
            });
            $(document).on('click', '.dropdown-option', function() {
                const val = $(this).data('value');
                if (val !== undefined) {
                    $('#status-input').val(val);
                    $(this).closest('.relative').find('.selected-label').text($(this).text());
                    $('#filter-form').submit();
                }
            });
            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
            });

            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                loadOrders('?' + $(this).serialize());
                window.history.pushState({}, '', '?' + $(this).serialize());
            });
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'));
                loadOrders(url.search);
                window.history.pushState({}, '', url.href);
            });

            window.cancelOrder = function(id, symbol) {
                confirmAction('{{ __('Cancel Order?') }}',
                    '{{ __('Are you sure you want to cancel order for') }} ' + symbol +
                    '? {{ __('Locked credit/margin if any will be refunded.') }}',
                    function() {
                        $.post("{{ route('admin.forex-trading.orders.cancel') }}", {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        }).done(res => {
                            toastNotification(res.status, res.message);
                            if (res.status === 'success') loadOrders(window.location.search);
                        });
                    });
            };

            window.deleteOrder = function(id, symbol) {
                confirmAction('{{ __('Delete Order?') }}', '{{ __('Remove record for') }} ' + symbol +
                    '. {{ __('No account updates will be made.') }}',
                    function() {
                        $.post("{{ route('admin.forex-trading.orders.delete') }}", {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        }).done(res => {
                            toastNotification(res.status, res.message);
                            if (res.status === 'success') loadOrders(window.location.search);
                        });
                    });
            };
        });
    </script>
@endpush
