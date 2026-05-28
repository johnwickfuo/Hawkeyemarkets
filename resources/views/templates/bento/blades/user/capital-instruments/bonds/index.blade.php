@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- ANALYTICS HEADER --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-accent-primary/50 transition-all duration-500">
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                            {{ __('Active Investments') }}</h2>
                        <div class="text-2xl font-mono font-bold text-white tracking-tight">
                            {{ getSetting('currency_symbol') }}{{ number_format($bond_analytics['total_invested'], 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-indigo-500/70 transition-all duration-500">
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                            {{ __('Expected Interest') }}</h2>
                        <div class="text-2xl font-mono font-bold text-emerald-400 tracking-tight">
                            +{{ getSetting('currency_symbol') }}{{ number_format($bond_analytics['expected_interest'], 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-emerald-500/70 transition-all duration-500">
                </div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">
                            {{ __('Total Payouts') }}</h2>
                        <div class="text-2xl font-mono font-bold text-white tracking-tight">
                            {{ getSetting('currency_symbol') }}{{ number_format($bond_analytics['total_payouts'], 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary-dark/60 backdrop-blur-sm border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div
                    class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-white/20 to-transparent group-hover:via-blue-500/70 transition-all duration-500">
                </div>
                <div class="relative z-10">
                    <h2 class="text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">{{ __('Active Bonds') }}
                    </h2>
                    <div class="text-2xl font-mono font-bold text-white">{{ $bond_analytics['active_count'] }}</div>
                </div>
            </div>
        </div>

        {{-- Navigation Toggle --}}
        <div class="flex items-center gap-6 border-b border-white/5">
            <button onclick="switchTab('marketplace')" id="tab-btn-marketplace"
                class="group relative pb-4 px-2 cursor-pointer">
                <span
                    class="text-base font-bold text-white group-hover:text-accent-primary transition-colors">{{ __('Marketplace') }}</span>
                <span id="tab-indicator-marketplace"
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-accent-primary transition-all"></span>
            </button>
            <button onclick="switchTab('portfolio')" id="tab-btn-portfolio" class="group relative pb-4 px-2 cursor-pointer">
                <span
                    class="text-base font-bold text-text-secondary group-hover:text-white transition-colors">{{ __('Current Holdings') }}</span>
                <span id="tab-indicator-portfolio"
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-transparent transition-all"></span>
            </button>
            <button onclick="switchTab('history')" id="tab-btn-history" class="group relative pb-4 px-2 cursor-pointer">
                <span
                    class="text-base font-bold text-text-secondary group-hover:text-white transition-colors">{{ __('Matured / History') }}</span>
                <span id="tab-indicator-history"
                    class="absolute bottom-0 left-0 w-full h-0.5 bg-transparent transition-all"></span>
            </button>
        </div>

        <div id="view-container">
            {{-- MARKETPLACE --}}
            <div id="view-marketplace" class="space-y-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 px-6 py-2 text-xs font-bold text-text-secondary uppercase tracking-wider">
                    <div class="col-span-5">{{ __('Bond Name') }}</div>
                    <div class="col-span-2 text-right">{{ __('Coupon Rate') }}</div>
                    <div class="col-span-3 text-right">{{ __('Maturity Date') }}</div>
                    <div class="col-span-2 text-right">{{ __('Action') }}</div>
                </div>

                <div class="space-y-2">
                    @forelse ($bonds as $bond)
                        <div
                            class="bg-secondary-dark/60 border border-white/5 rounded-2xl p-4 md:px-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            <div class="col-span-5 flex items-center gap-4">
                                <div>
                                    <div class="font-bold text-white">{{ $bond['name'] }}</div>
                                    <div class="text-xs text-text-secondary">{{ $bond['issuer'] }} • {{ $bond['cusip'] }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2 text-right">
                                <span class="font-mono font-bold text-emerald-400">{{ $bond['coupon'] }}%</span>
                            </div>
                            <div class="col-span-3 text-right">
                                <span class="text-sm text-text-secondary">{{ date('M d, Y', $bond['maturity']) }}</span>
                            </div>
                            <div class="col-span-2 flex justify-end">
                                <a href="{{ route('user.capital-instruments.bonds.buy', $bond['cusip']) }}"
                                    class="bg-accent-primary text-white rounded-lg px-4 py-2 text-sm font-bold">{{ __('Invest') }}</a>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-text-secondary">{{ __('No bonds available at the moment.') }}
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- HOLDINGS --}}
            <div id="view-portfolio" class="hidden space-y-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 px-6 py-2 text-xs font-bold text-text-secondary uppercase tracking-wider">
                    <div class="col-span-4">{{ __('Bond') }}</div>
                    <div class="col-span-2 text-right">{{ __('Principal') }}</div>
                    <div class="col-span-2 text-right">{{ __('Expected ROI') }}</div>
                    <div class="col-span-3 text-right">{{ __('Maturity Date') }}</div>
                    <div class="col-span-1"></div>
                </div>

                <div class="space-y-2">
                    @forelse ($active_holdings as $holding)
                        <div
                            class="bg-secondary-dark/60 border border-white/5 rounded-2xl p-4 md:px-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            <div class="col-span-4">
                                <div class="font-bold text-white">{{ $holding->bond_name }}</div>
                                <div class="text-xs text-text-secondary">{{ $holding->cusip }}</div>
                            </div>
                            <div class="col-span-2 text-right font-mono text-white">
                                {{ getSetting('currency_symbol') }}{{ number_format($holding->amount, 2) }}
                            </div>
                            <div class="col-span-2 text-right font-mono text-emerald-400">
                                +{{ getSetting('currency_symbol') }}{{ number_format($holding->interest_amount, 2) }}
                            </div>
                            <div class="col-span-3 text-right">
                                <div class="text-sm text-white">{{ date('M d, Y', $holding->maturity_date) }}</div>
                                <div class="text-[10px] text-text-secondary">
                                    {{ \Carbon\Carbon::createFromTimestamp($holding->maturity_date)->diffForHumans() }}
                                </div>
                            </div>
                            <div class="col-span-1 text-right">
                                <span
                                    class="text-[10px] bg-blue-500/10 text-blue-400 px-2 py-1 rounded uppercase font-bold">{{ __('Locked') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-text-secondary">
                            {{ __('You don\'t have any active bond investments.') }}</div>
                    @endforelse
                </div>
            </div>

            {{-- HISTORY --}}
            <div id="view-history" class="hidden space-y-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 px-6 py-2 text-xs font-bold text-text-secondary uppercase tracking-wider">
                    <div class="col-span-1 border-r border-white/5">{{ __('#') }}</div>
                    <div class="col-span-5">{{ __('Bond / Description') }}</div>
                    <div class="col-span-3 text-right">{{ __('Amount') }}</div>
                    <div class="col-span-3 text-right">{{ __('Date') }}</div>
                </div>

                <div class="space-y-2">
                    @forelse ($holding_histories as $history)
                        <div
                            class="bg-secondary-dark/60 border border-white/5 rounded-2xl p-4 md:px-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            <div class="col-span-1 text-xs text-text-secondary border-r border-white/5 font-mono">
                                {{ $history->id }}
                            </div>
                            <div class="col-span-5">
                                <div class="font-bold text-white">{{ $history->cusip }}</div>
                                <div
                                    class="text-[10px] font-bold uppercase tracking-tighter {{ $history->transaction_type == 'buy' ? 'text-amber-400' : 'text-emerald-400' }}">
                                    {{ $history->transaction_type == 'buy' ? __('Investment (Fixed Term)') : __('Maturity Payout') }}
                                </div>
                            </div>
                            <div
                                class="col-span-3 text-right font-mono {{ $history->transaction_type == 'buy' ? 'text-rose-400' : 'text-emerald-400' }}">
                                {{ $history->transaction_type == 'buy' ? '-' : '+' }}{{ getSetting('currency_symbol') }}{{ number_format($history->amount, 2) }}
                            </div>
                            <div class="col-span-3 text-right text-sm text-text-secondary">
                                {{ $history->created_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-text-secondary">{{ __('No history available.') }}</div>
                    @endforelse

                    <div class="mt-4">
                        {{ $holding_histories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const tabs = ['marketplace', 'portfolio', 'history'];
            tabs.forEach(t => {
                document.getElementById('view-' + t).classList.add('hidden');
                document.getElementById('tab-btn-' + t).querySelector('span').classList.remove('text-white');
                document.getElementById('tab-btn-' + t).querySelector('span').classList.add('text-text-secondary');
                document.getElementById('tab-indicator-' + t).classList.remove('bg-accent-primary');
                document.getElementById('tab-indicator-' + t).classList.add('bg-transparent');
            });

            document.getElementById('view-' + tab).classList.remove('hidden');
            document.getElementById('tab-btn-' + tab).querySelector('span').classList.remove('text-text-secondary');
            document.getElementById('tab-btn-' + tab).querySelector('span').classList.add('text-white');
            document.getElementById('tab-indicator-' + tab).classList.remove('bg-transparent');
            document.getElementById('tab-indicator-' + tab).classList.add('bg-accent-primary');
        }
    </script>
@endsection
