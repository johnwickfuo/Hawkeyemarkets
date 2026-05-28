@php
    // Mock Data for Bonds Interface - In a real app, this would be passed from Controller
    // but we are using the $bonds variable passed from the controller as per instructions.
    // We can add some portfolio mock data here if needed, or assume $portfolio is globally available or passed.
    // $portfolio = [
    //     'total_value' => 25000.0,
    //     'cash_balance' => 12000.0,
    // ];
@endphp

@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">{{ __('Government Bonds') }}</h1>
                <p class="text-text-secondary text-sm mt-1">
                    {{ __('Secure fixed-income investments from top global economies.') }}</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 flex items-center gap-3">
                <div class="text-xs text-text-secondary font-bold uppercase tracking-wider">{{ __('Available Cash') }}</div>
                <div class="text-lg font-mono font-bold text-emerald-400">
                    {{ getSetting('currency_symbol') }}{{ number_format(auth()->user()->balance, 2) }}</div>
            </div>
        </div>

        {{-- MARKETPLACE LIST VIEW --}}
        <div id="view-marketplace" class="space-y-4">
            {{-- Search Bar --}}
            <div class="relative group mb-6">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-text-secondary group-focus-within:text-accent-primary transition-colors"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <input type="text" id="bond-search" placeholder="{{ __('Search countries, bonds, or symbols...') }}"
                    onkeyup="filterBonds()"
                    class="w-full bg-[#0f1219] border border-white/10 rounded-xl py-3.5 pl-12 pr-4 text-base text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all">
            </div>


            {{-- List Header --}}
            <div
                class="hidden md:grid grid-cols-12 gap-4 px-6 py-2 text-xs font-bold text-text-secondary uppercase tracking-wider">
                <div class="col-span-4">{{ __('Issuer / Name') }}</div>
                <div class="col-span-2 text-right">{{ __('Coupon') }}</div>
                <div class="col-span-3 text-right">{{ __('Maturity') }}</div>
                <div class="col-span-2 text-right">{{ __('Rating') }}</div>
                <div class="col-span-1 text-right">{{ __('Action') }}</div>
            </div>


            {{-- Scrollable List --}}
            <div class="space-y-2" id="bond-list">
                @forelse ($bonds as $index => $bond)
                    <div class="block group bond-item {{ $index >= 20 ? 'hidden' : '' }}"
                        data-name="{{ strtolower($bond['name'] ?? '') }}"
                        data-issuer="{{ strtolower($bond['issuer'] ?? '') }}"
                        data-cusip="{{ strtolower($bond['cusip'] ?? '') }}">
                        <div
                            class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-4 md:px-6 md:py-4 hover:border-accent-primary/40 hover:bg-white/[0.02] transition-all grid grid-cols-1 md:grid-cols-12 gap-4 items-center relative overflow-hidden">

                            {{-- Hover Gradient Line --}}
                            <div
                                class="absolute left-0 inset-y-0 w-[2px] bg-accent-primary opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>

                            {{-- Bond Info --}}
                            <div class="col-span-12 md:col-span-4 flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-xl overflow-hidden shrink-0">
                                    <span
                                        class="fi fi-{{ $bond['flag'] ?? 'xx' }} w-full h-full bg-cover bg-center"></span>
                                </div>
                                <div>
                                    <div
                                        class="font-heading font-bold text-white text-base group-hover:text-accent-primary transition-colors">
                                        {{ $bond['name'] }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <a href="https://www.finra.org/finra-data/fixed-income/bond?cusip={{ $bond['cusip'] }}&bondType=TS"
                                            target="_blank"
                                            class="text-xs font-bold text-text-secondary bg-white/5 px-1.5 py-0.5 rounded border border-white/5 w-[85px] text-center truncate hover:text-accent-primary hover:border-accent-primary/50 transition-all flex items-center justify-center gap-1 group/cusip"
                                            title="{{ __('Verify') }} - {{ $bond['cusip'] }}">
                                            <span>{{ $bond['cusip'] }}</span>
                                            <svg class="w-2.5 h-2.5 opacity-0 group-hover:block group-hover/cusip:opacity-100 transition-opacity"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                        <span
                                            class="text-[10px] text-text-secondary font-medium hidden sm:inline-block truncate max-w-[150px]"
                                            title="{{ $bond['issuer'] }}">{{ $bond['issuer'] }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Coupon --}}
                            <div class="col-span-6 md:col-span-2 flex md:block items-center justify-between md:text-right">
                                <span
                                    class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Coupon') }}</span>
                                <span
                                    class="font-mono font-bold text-white text-lg">{{ number_format($bond['coupon'], 3) }}%</span>
                            </div>

                            {{-- Maturity --}}
                            <div class="col-span-6 md:col-span-3 flex md:block items-center justify-between md:text-right">
                                <span
                                    class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Maturity') }}</span>
                                <span
                                    class="font-mono font-bold text-text-secondary text-base">{{ date('d M, Y', $bond['maturity']) }}</span>
                            </div>

                            {{-- Rating --}}
                            <div class="col-span-6 md:col-span-2 flex md:block items-center justify-between md:text-right">
                                <span
                                    class="md:hidden text-xs text-text-secondary font-bold uppercase">{{ __('Rating') }}</span>
                                <span
                                    class="font-bold text-text-secondary bg-white/5 px-2 py-1 rounded-lg border border-white/5 text-xs">{{ $bond['rating'] }}</span>
                            </div>

                            {{-- Invest Action --}}
                            <div class="col-span-12 md:col-span-1 flex justify-end mt-4 md:mt-0">
                                <button onclick="openInvestModal(@js($bond))"
                                    class="w-full md:w-auto bg-accent-primary hover:bg-accent-primary/90 text-white rounded-lg px-3 py-2 font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-accent-primary/20 group/btn cursor-pointer">
                                    <span>{{ __('Invest') }}</span>
                                </button>
                            </div>

                        </div>
                    </div>
                @empty
                    @if (isset($message))
                        <div class="col-span-12 flex justify-center py-12">
                            <div
                                class="bg-rose-500/10 border border-rose-500/20 rounded-xl max-w-md w-full relative overflow-hidden text-center p-6">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-rose-500/5 to-transparent pointer-events-none">
                                </div>
                                <div class="relative z-10 flex flex-col items-center">
                                    <div
                                        class="w-10 h-10 rounded-full bg-rose-500/10 flex items-center justify-center mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="text-rose-500 w-5 h-5" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12">
                                            </line>
                                            <line x1="12" y1="16" x2="12.01" y2="16">
                                            </line>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-white mb-2">{{ __('Unable to Load Bonds') }}
                                    </h3>
                                    <p class="text-rose-200/70 text-sm leading-relaxed">
                                        {{ $message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-span-12 text-center py-12 text-text-secondary">
                            {{ __('No bonds found.') }}
                        </div>
                    @endif
                @endforelse
            </div>

            {{-- Load More Action --}}
            @if (count($bonds) >= 20)
                <div class="text-center pt-6 pb-4" id="load-more-container">
                    <button onclick="loadMoreBonds()"
                        class="px-6 py-2 rounded-xl bg-white/5 border border-white/10 text-sm font-bold text-text-secondary hover:text-white hover:bg-white/10 transition-colors w-full md:w-auto cursor-pointer">
                        {{ __('Load More Bonds') }}
                    </button>
                    <div class="text-[10px] text-text-secondary mt-2">{{ __('Showing') }} <span
                            id="visible-count">20</span> {{ __('of') }}
                        {{ count($bonds) }} {{ __('Bonds') }}</div>
                </div>
            @endif
        </div>
    </div>


    {{-- INVEST MODAL --}}
    <div id="invest-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity opacity-0" id="invest-modal-backdrop">
        </div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                {{-- Modal Panel --}}
                <div class="relative transform overflow-hidden rounded-2xl bg-[#0f1219] border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    id="invest-modal-panel">

                    <div class="absolute top-0 right-0 pt-4 pr-4 z-10">
                        <button type="button" onclick="closeInvestModal()"
                            class="rounded-lg bg-white/5 p-2 text-text-secondary hover:text-white hover:bg-white/10 transition-colors focus:outline-none cursor-pointer">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center p-2 shadow-lg">
                                <span id="modal-flag" class="fi w-full h-full bg-cover bg-center rounded-md"></span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white mb-0.5 leading-tight" id="modal-bond-name"></h3>
                                <div class="mb-2">
                                    <p class="text-sm text-text-secondary leading-tight" id="modal-bond-issuer"></p>
                                    <p class="text-[10px] text-text-secondary font-mono opacity-50 mt-0.5"
                                        id="modal-bond-cusip"></p>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        class="text-xs font-bold text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded border border-emerald-400/20"
                                        id="modal-bond-yield"></span>
                                    <span
                                        class="text-xs font-medium text-text-secondary bg-white/5 px-2 py-0.5 rounded border border-white/5"
                                        id="modal-bond-rating"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Available Balance --}}
                        <div
                            class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3 border border-white/10 mb-5">
                            <span class="text-sm font-bold text-text-secondary">{{ __('Available Balance') }}</span>
                            <span class="text-lg font-mono font-bold text-white">
                                {{ getSetting('currency_symbol') }}{{ number_format(auth()->user()->balance, getSetting('decimal_places')) }}
                            </span>
                        </div>

                        <form id="invest-form" onsubmit="event.preventDefault(); submitInvestment();" class="space-y-5">
                            <input type="hidden" id="modal-bond-id" name="bond_id">

                            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 flex gap-3">
                                <svg class="w-5 h-5 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm text-blue-100">
                                    {{ __('You are investing in government bonds. Principal is returned at maturity, and coupons are paid periodically.') }}
                                </p>
                            </div>

                            {{-- Amount Input --}}
                            <div>
                                <label for="invest-amount"
                                    class="block text-sm font-bold text-text-secondary uppercase tracking-wider mb-2">
                                    {{ __('Investment Amount') }} ({{ getSetting('currency_symbol') }})
                                </label>
                                <div class="relative">
                                    <input type="number" step="100" min="100" name="amount"
                                        id="invest-amount" required
                                        class="w-full bg-secondary-dark border border-white/10 rounded-xl py-3.5 pl-4 pr-4 text-white placeholder-text-secondary focus:border-accent-primary focus:ring-0 transition-all font-mono font-bold"
                                        placeholder="Min 100">
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-xs text-text-secondary">{{ __('Price') }}: <span
                                            id="modal-bond-price" class="text-white font-bold"></span></span>
                                    <span class="text-xs text-text-secondary">{{ __('Date of Issue') }}: <span
                                            id="modal-bond-issue-date" class="text-white font-bold"></span></span>
                                    <span class="text-xs text-text-secondary">{{ __('Maturity') }}: <span
                                            id="modal-bond-maturity" class="text-white font-bold"></span></span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <a href="#" id="modal-verify-link" target="_blank"
                                    class="w-full bg-white/5 hover:bg-white/10 text-white border border-white/10 rounded-xl py-3.5 font-bold text-lg transition-all flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-5 h-5 text-text-secondary" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    <span>{{ __('Verify') }}</span>
                                </a>
                                <button type="submit"
                                    class="w-full bg-accent-primary hover:bg-accent-primary/90 text-white rounded-xl py-3.5 font-bold text-lg shadow-lg hover:shadow-accent-primary/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                    {{ __('Confirm Investment') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const totalItems = {{ count($bonds) }};
        let visibleItems = 20;

        function loadMoreBonds() {
            const hiddenItems = document.querySelectorAll('.bond-item.hidden');
            let count = 0;
            hiddenItems.forEach(item => {
                if (count < 20) {
                    item.classList.remove('hidden');
                    count++;
                }
            });
            visibleItems += count;
            document.getElementById('visible-count').innerText = Math.min(visibleItems, totalItems);
            if (visibleItems >= totalItems) {
                document.getElementById('load-more-container').style.display = 'none';
            }
        }

        function filterBonds() {
            const input = document.getElementById('bond-search');
            const filter = input.value.toLowerCase();
            const items = document.getElementsByClassName('bond-item');
            const loadMoreContainer = document.getElementById('load-more-container');

            if (filter === '') {
                Array.from(items).forEach((item, index) => {
                    if (index < visibleItems) item.classList.remove('hidden');
                    else item.classList.add('hidden');
                });
                if (visibleItems < totalItems && loadMoreContainer) loadMoreContainer.style.display = 'block';
                else if (loadMoreContainer) loadMoreContainer.style.display = 'none';
                return;
            }

            if (loadMoreContainer) loadMoreContainer.style.display = 'none';

            Array.from(items).forEach(item => {
                const name = item.getAttribute('data-name');
                const issuer = item.getAttribute('data-issuer');
                const cusip = item.getAttribute('data-cusip');

                if (name.indexOf(filter) > -1 || issuer.indexOf(filter) > -1 || cusip.indexOf(filter) > -1) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        // --- Invest Modal Logic ---
        const investModal = document.getElementById('invest-modal');
        const investModalBackdrop = document.getElementById('invest-modal-backdrop');
        const investModalPanel = document.getElementById('invest-modal-panel');

        const modalBondName = document.getElementById('modal-bond-name');
        const modalBondIssuer = document.getElementById('modal-bond-issuer');
        const modalBondCusip = document.getElementById('modal-bond-cusip');
        const modalBondYield = document.getElementById('modal-bond-yield');
        const modalBondRating = document.getElementById('modal-bond-rating');
        const modalBondPrice = document.getElementById('modal-bond-price');
        const modalBondIssueDate = document.getElementById('modal-bond-issue-date');
        const modalBondMaturity = document.getElementById('modal-bond-maturity');
        const modalFlag = document.getElementById('modal-flag');
        const modalBondId = document.getElementById('modal-bond-id');

        function openInvestModal(bond) {
            modalBondName.innerText = bond.name;
            modalBondIssuer.innerText = bond.issuer;
            modalBondCusip.innerText = 'CUSIP: ' + bond.cusip;
            modalBondYield.innerText = bond.coupon + '% ' + @json(__('Coupon'));
            modalBondRating.innerText = bond.rating;
            modalBondPrice.innerText = @json(__('Market Price')); // Price not available in list

            // Helper to format unix timestamp to Date string
            const formatDate = (timestamp) => {
                if (!timestamp) return '-';
                // Check if timestamp is in seconds (unix) or millis. Usually Unix is seconds.
                // JS Date expects millis. If strict Unix timestamp (seconds), multiply by 1000.
                // Assuming standard PHP/Unix timestamp in seconds based on "unix timestamp".
                const date = new Date(timestamp * 1000);
                return date.toLocaleDateString('en-GB', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            };

            modalBondIssueDate.innerText = formatDate(bond.issue_date);
            modalBondMaturity.innerText = formatDate(bond.maturity); // User said maturity is also timestamp now
            modalBondId.value = bond.cusip;

            // Set flag class
            modalFlag.className = `fi fi-${bond.flag} w-full h-full bg-cover bg-center rounded-md`;

            // Set Verify Link
            const verifyLink = document.getElementById('modal-verify-link');
            if (verifyLink) {
                verifyLink.href = `https://www.finra.org/finra-data/fixed-income/bond?cusip=${bond.cusip}&bondType=TS`;
            }

            investModal.classList.remove('hidden');
            // Animation
            setTimeout(() => {
                investModalBackdrop.classList.remove('opacity-0');
                investModalPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            }, 10);
        }

        function closeInvestModal() {
            investModalBackdrop.classList.add('opacity-0');
            investModalPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => {
                investModal.classList.add('hidden');
            }, 300);
        }

        function submitInvestment() {
            // Mock submission
            const amount = document.getElementById('invest-amount').value;
            alert(`Investment of ${amount} submitted successfully! (Mock Action)`);
            closeInvestModal();
        }
    </script>

    {{-- Flag Icons CSS (Assuming it's not already included in layout, if it is, this can be removed) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css" />
@endsection
