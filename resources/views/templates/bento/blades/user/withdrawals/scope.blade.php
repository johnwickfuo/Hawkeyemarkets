@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8">
        {{-- Creative Header --}}
        <div class="relative overflow-hidden rounded-2xl bg-secondary border border-white/5 p-6 md:p-8">
            {{-- Background decorative elements (Dynamic based on status) --}}
            @php
                $statusColors = [
                    'completed' => 'emerald',
                    'pending' => 'yellow',
                    'failed' => 'red',
                    'partial_payment' => 'orange',
                ];
                // Ensure status is valid or default to 'completed'
                $safeStatus = isset($status) && isset($statusColors[$status]) ? $status : 'completed';
                $color = $statusColors[$safeStatus];

                $statusIcons = [
                    'completed' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'failed' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    'partial_payment' =>
                        'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                ];
                $icon = $statusIcons[$safeStatus] ?? 'M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z';
            @endphp

            <div
                class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-{{ $color }}-500/5 blur-3xl pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-start gap-5">
                    <div
                        class="p-4 bg-white/5 rounded-2xl border border-white/10 hidden md:flex items-center justify-center backdrop-blur-sm shadow-xl">
                        <svg class="w-8 h-8 text-{{ $color }}-500 transition-transform hover:scale-110 duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">{{ $page_title }}</h1>
                        <p class="text-text-secondary text-sm md:text-base mt-2 max-w-xl leading-relaxed">
                            {{ __('Viewing all :status withdrawal records and transaction details associated with your account.', ['status' => __($scopeName)]) }}
                        </p>
                    </div>
                </div>

                {{-- Summary Card (Total Amount) --}}
                <div id="summary-card-container">
                    <div
                        class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-5 min-w-[200px] flex flex-col items-center md:items-end justify-center">
                        <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1">
                            {{ __('Total :status', ['status' => __($scopeName)]) }}</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-2xl font-black text-white leading-none">
                                {{ number_format($totalAmount, 2) }}
                            </span>
                            <span class="text-xs font-bold text-{{ $color }}-500 uppercase tracking-tighter">
                                {{ getSetting('currency') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-secondary border border-white/5 rounded-2xl p-4 flex flex-wrap items-center gap-4">
            <form id="scope-filter-form" action="{{ url()->current() }}" class="flex flex-wrap items-center gap-4 w-full">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[240px]">
                    <input type="text" name="search" placeholder="{{ __('Search by reference or amount...') }}"
                        value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-base text-white placeholder-text-secondary focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all w-full">
                    <svg class="w-4 h-4 text-text-secondary absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                {{-- Date Range --}}
                <div class="flex items-center gap-2">
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-base text-white focus:outline-none focus:border-accent-primary transition-all">
                    <span class="text-text-secondary text-xs uppercase font-bold">{{ __('To') }}</span>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-base text-white focus:outline-none focus:border-accent-primary transition-all">
                </div>

                {{-- Custom Method Dropdown --}}
                @php
                    $activeMethod = $withdrawal_methods->find(request('method_id'));
                @endphp
                <div class="relative custom-dropdown min-w-[180px]" data-name="method_id">
                    <input type="hidden" name="method_id" value="{{ request('method_id') }}">
                    <button type="button"
                        class="bg-secondary border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:ring-1 focus:ring-accent-primary outline-none cursor-pointer flex items-center justify-between w-full dropdown-trigger">
                        <span class="dropdown-label">{{ $activeMethod ? $activeMethod->name : __('All Methods') }}</span>
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="absolute top-full left-0 mt-2 w-full bg-[#0F172A] border border-white/10 rounded-xl shadow-2xl z-50 flex flex-col py-1 overflow-hidden hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                        <div data-value=""
                            class="px-4 py-2.5 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                            {{ __('All Methods') }}</div>
                        @foreach ($withdrawal_methods as $wm)
                            <div data-value="{{ $wm->id }}"
                                class="px-4 py-2.5 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer dropdown-item">
                                {{ $wm->name }}</div>
                        @endforeach
                    </div>
                </div>

                {{-- Reset Button --}}
                <a href="{{ url()->current() }}"
                    class="p-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-text-secondary hover:text-white transition-all cursor-pointer"
                    title="{{ __('Clear All Filters') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                </a>

                <button type="submit"
                    class="px-6 py-2.5 bg-accent-primary hover:bg-accent-primary-hover text-white text-sm font-bold rounded-xl transition-all active:scale-95 shadow-lg shadow-accent-primary/20 cursor-pointer">
                    {{ __('Apply') }}
                </button>
            </form>
        </div>

        {{-- Scoped Table --}}
        <div id="withdrawals-history-wrapper"
            class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative">
            <div id="withdrawals-loading-spinner"
                class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                </div>
                <p class="mt-3 text-text-secondary font-medium animate-pulse">{{ __('Syncing records...') }}</p>
            </div>

            <div id="withdrawals-table-content">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5 text-left">
                                <th
                                    class="px-6 py-5 text-xs font-bold text-text-secondary uppercase tracking-wider w-[35%]">
                                    {{ __('Transaction') }}</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group hover:text-white transition-colors">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'amount', 'direction' => request('sort') == 'amount' && request('direction') == 'desc' ? 'asc' : 'desc']) }}"
                                        class="sort-link flex items-center gap-1">
                                        {{ __('Amount') }}
                                        <svg class="w-3 h-3 transition-transform {{ request('sort') == 'amount' ? 'text-accent-primary' : 'text-text-secondary/50 group-hover:text-white' }} {{ request('sort') == 'amount' && request('direction') == 'asc' ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </th>
                                <th class="px-6 py-5 text-xs font-bold text-text-secondary uppercase tracking-wider">
                                    {{ __('Method') }}</th>
                                <th
                                    class="px-6 py-5 text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group hover:text-white transition-colors">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'desc' ? 'asc' : 'desc']) }}"
                                        class="sort-link flex items-center gap-1">
                                        {{ __('Date') }}
                                        <svg class="w-3 h-3 transition-transform {{ request('sort') == 'created_at' || !request('sort') ? 'text-accent-primary' : 'text-text-secondary/50 group-hover:text-white' }} {{ request('sort') == 'created_at' && request('direction') == 'asc' ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </th>
                                <th class="px-6 py-5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($withdrawals as $withdrawal)
                                <tr class="hover:bg-white/5 transition-all duration-200 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-2xl bg-{{ $color }}-500/10 flex items-center justify-center text-{{ $color }}-500 group-hover:bg-{{ $color }}-500 group-hover:text-white transition-all duration-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex flex-col">
                                                <a href="{{ route('user.withdrawals.view', $withdrawal->transaction_reference) }}"
                                                    class="text-white font-bold text-sm hover:text-accent-primary transition-colors hover:underline font-mono tracking-tight">
                                                    {{ strlen($withdrawal->transaction_reference) > 6 ? substr($withdrawal->transaction_reference, 0, 3) . '...' . substr($withdrawal->transaction_reference, -3) : $withdrawal->transaction_reference }}
                                                </a>
                                                <p class="text-text-secondary text-[10px] mt-1 font-mono opacity-60"
                                                    title="{{ $withdrawal->transaction_hash }}">
                                                    {{ $withdrawal->transaction_hash ? (strlen($withdrawal->transaction_hash) > 6 ? substr($withdrawal->transaction_hash, 0, 8) . '...' . substr($withdrawal->transaction_hash, -8) : $withdrawal->transaction_hash) : '---' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-white font-bold text-base">
                                                {{ number_format($withdrawal->amount, 2) }}
                                                <span class="text-[10px] text-text-secondary uppercase">
                                                    {{ getSetting('currency') }}</span>
                                            </span>
                                            @if ($withdrawal->currency != getSetting('currency'))
                                                <span class="text-[10px] text-text-secondary mt-1 font-medium italic">
                                                    ≈ {{ number_format($withdrawal->converted_amount, 8) }}
                                                    {{ $withdrawal->currency }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-xs font-bold text-white shadow-sm">
                                            {{ $wm_name = $withdrawal->withdrawalMethod->name ?? __('Unknown') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-white text-sm font-medium">{{ $withdrawal->created_at->format('M d, Y') }}</span>
                                            <span
                                                class="text-text-secondary text-[10px] mt-0.5">{{ $withdrawal->created_at->format('H:i A') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('user.withdrawals.view', $withdrawal->transaction_reference) }}"
                                            class="inline-flex items-center justify-center p-2.5 rounded-xl bg-white/5 hover:bg-{{ $color }}-500/20 text-text-secondary hover:text-{{ $color }}-500 transition-all active:scale-90 border border-transparent hover:border-{{ $color }}-500/20"
                                            title="{{ __('View Details') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div
                                                class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mb-6 text-text-secondary border border-white/10 shadow-inner">
                                                <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2v-1m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h4 class="text-white font-bold text-xl mb-2">{{ __('No Records Found') }}
                                            </h4>
                                            <p class="text-text-secondary text-sm mb-8">
                                                {{ __('We couldn\'t find any records for :status withdrawals at this time.', ['status' => strtolower(__($scopeName))]) }}
                                            </p>
                                            <a href="{{ route('user.withdrawals.new') }}"
                                                class="px-8 py-3 bg-{{ $color }}-500 hover:bg-{{ $color }}-600 text-white font-bold rounded-xl transition-all shadow-lg active:scale-95">
                                                {{ __('Request Withdrawal') }}
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($withdrawals->hasPages())
                    <div class="p-6 border-t border-white/5 ajax-pagination">
                        {{ $withdrawals->links('templates.bento.blades.partials.pagination') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Helper to load content via AJAX
            function loadScopedTable(url) {
                $('#withdrawals-loading-spinner').removeClass('hidden').addClass('flex');

                $.get(url, function(data) {
                    const $newData = $(data);
                    $('#withdrawals-table-content').html($newData.find('#withdrawals-table-content')
                        .html());
                    $('#summary-card-container').html($newData.find('#summary-card-container').html());
                }).always(function() {
                    setTimeout(() => $('#withdrawals-loading-spinner').addClass('hidden').removeClass(
                            'flex'),
                        400);
                });
            }

            // AJAX Pagination & Sorting
            $(document).on('click', '.ajax-pagination a, .sort-link', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');

                if ($(this).hasClass('ajax-pagination')) {
                    $('html, body').animate({
                        scrollTop: $("#withdrawals-history-wrapper").offset().top - 100
                    }, 500);
                }

                loadScopedTable(url);
                // Update URL in browser without refresh
                window.history.pushState(null, '', url);
            });

            // Custom Dropdowns Logic
            $(document).on('click', '.dropdown-trigger', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $menu = $(this).siblings('.dropdown-menu');

                $('.dropdown-menu').not($menu).addClass('hidden');
                $('.dropdown-trigger svg').not($(this).find('svg')).removeClass('rotate-180');

                $menu.toggleClass('hidden');
                $(this).find('svg').toggleClass('rotate-180');
            });

            $(document).on('click', '.dropdown-item', function(e) {
                e.stopPropagation();
                var $container = $(this).closest('.custom-dropdown');
                var value = $(this).data('value');
                var label = $(this).text();

                $container.find('input[type="hidden"]').val(value);
                $container.find('.dropdown-label').text(label);
                $container.find('.dropdown-menu').addClass('hidden');
                $container.find('.dropdown-trigger svg').removeClass('rotate-180');

                // Trigger form submission
                $('#scope-filter-form').submit();
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-trigger svg').removeClass('rotate-180');
            });

            // AJAX Filter Form
            $('#scope-filter-form').on('submit', function(e) {
                e.preventDefault();
                const url = $(this).attr('action') + '?' + $(this).serialize();
                loadScopedTable(url);
                window.history.pushState(null, '', url);
            });

            // Auto-submit search after delay
            let searchTimer;
            $('input[name="search"]').on('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    $('#scope-filter-form').submit();
                }, 600);
            });
        });
    </script>
@endsection
