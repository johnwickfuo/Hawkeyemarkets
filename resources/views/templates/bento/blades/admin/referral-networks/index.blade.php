@extends('templates.bento.blades.admin.layouts.admin')

@push('scripts')
    <script>
        function toggleTreeNode(nodeId) {
            const container = document.getElementById(nodeId);
            const button = document.querySelector(`.tree-toggle-btn[data-target="${nodeId}"] svg`);

            if (container) {
                if (container.classList.contains('hidden')) {
                    container.classList.remove('hidden');
                    if (button) button.classList.add('rotate-90');
                } else {
                    container.classList.add('hidden');
                    if (button) button.classList.remove('rotate-90');
                }
            }
        }
    </script>
@endpush

@section('content')
    <div class="space-y-8 pb-12">
        {{-- Glassy Referral Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-secondary border border-white/5 p-8 md:p-12 shadow-2xl">
            <div
                class="absolute -top-24 -right-24 w-96 h-96 bg-accent-primary/10 rounded-full blur-[120px] pointer-events-none">
            </div>
            <div
                class="absolute -bottom-24 -left-24 w-64 h-64 bg-purple-500/10 rounded-full blur-[100px] pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="max-w-2xl text-center lg:text-left">
                    <span
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-accent-primary/10 border border-accent-primary/20 text-accent-primary text-xs font-black uppercase tracking-widest mb-6 animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent-primary"></span>
                        {{ __('Network Overview') }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black text-white leading-[1.1] mb-6 tracking-tighter">
                        {{ __('Global Referral &') }} <br>
                        <span class="text-accent-primary decoration-accent-primary/20 underline underline-offset-8">
                            {{ __('Affiliate System') }} </span>
                    </h1>
                    <p class="text-text-secondary text-base md:text-lg leading-relaxed mb-8 opacity-80">
                        {{ __('Monitor platform-wide referral activity across :levels supported hierarchy levels. Track total payouts, identify top influencers, and analyze growth.', ['levels' => $total_levels]) }}
                    </p>
                </div>

                {{-- Visual Element (illustration or glass box) --}}
                <div class="hidden lg:block relative group">
                    <div
                        class="absolute inset-0 bg-accent-primary rounded-3xl blur-3xl opacity-20 group-hover:opacity-30 transition-opacity">
                    </div>
                    <div
                        class="relative w-80 h-80 bg-white/5 backdrop-blur-3xl border border-white/10 rounded-3xl p-8 flex flex-col justify-center items-center shadow-2xl">
                        <div
                            class="w-24 h-24 bg-accent-primary/20 rounded-full flex items-center justify-center mb-8 shadow-inner shadow-accent-primary/50 border border-accent-primary/20">
                            <svg class="w-12 h-12 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-center space-y-4">
                            <h3 class="text-white font-black text-2xl tracking-tighter">{{ __('Growth Engine') }}</h3>
                            <p class="text-text-secondary text-sm leading-relaxed">
                                {{ __('Tracking the entire platform expansion.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-secondary border border-white/5 rounded-2xl p-6 hover:border-accent-primary/30 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-accent-primary" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 14H11V11H13V16zm0-7H11V7H13V9z">
                        </path>
                    </svg>
                </div>
                <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-3">
                    {{ __('Total Payouts') }}</p>
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-3xl font-black text-white decoration-accent-primary/20 hover:text-accent-primary transition-colors cursor-default">
                        {{ showAmount($total_commissions) }}
                    </span>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-2xl p-6 hover:border-purple-500/30 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                        </path>
                    </svg>
                </div>
                <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-3">
                    {{ __('Total Referrals') }}</p>
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-3xl font-black text-white transition-colors cursor-default group-hover:text-purple-500">
                        {{ number_format($total_referrals) }}
                    </span>
                    <span class="text-xs font-bold text-text-secondary">{{ __('Users') }}</span>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-2xl p-6 hover:border-blue-500/30 transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-blue-500">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"></path>
                    </svg>
                </div>
                <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-3">
                    {{ __('Configured Tiers') }}</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-white transition-colors cursor-default group-hover:text-blue-500">
                        {{ $total_levels }}
                    </span>
                    <span class="text-xs font-bold text-text-secondary">{{ __('Levels') }}</span>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-2xl p-6 hover:border-emerald-500/30 transition-all group overflow-hidden relative">
                <div
                    class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-emerald-500">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z">
                        </path>
                    </svg>
                </div>
                <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-3">
                    {{ __('Top Referrer') }}</p>
                <div class="flex items-center gap-3">
                    @if (count($top_referrers) > 0)
                        @php $top = $top_referrers[0]; @endphp
                        @if ($top->photo)
                            <img src="{{ asset('storage/profile/' . $top->photo) }}"
                                class="w-8 h-8 rounded-full border border-emerald-500/30 object-cover shrink-0">
                        @else
                            <div
                                class="w-8 h-8 rounded-full border border-emerald-500/30 bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold text-xs shrink-0">
                                {{ strtoupper(substr($top->first_name, 0, 1)) }}{{ strtoupper(substr($top->last_name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-white font-bold text-sm truncate">{{ $top->username }}</p>
                            <p class="text-[10px] text-text-secondary font-mono">{{ $top->network_size }}
                                {{ __('Network Size') }}</p>
                        </div>
                    @else
                        <span class="text-sm font-bold text-text-secondary">{{ __('None Yet') }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Top Referrers Leaderboard --}}
            <div class="space-y-6">
                <div class="bg-secondary border border-white/5 rounded-3xl p-8 sticky top-24 shadow-xl">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-lg font-black text-white tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            {{ __('Top Affiliates') }}
                        </h2>
                        <div class="flex items-center bg-white/5 rounded-lg p-1 border border-white/10 shrink-0">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'size']) }}"
                                class="px-2.5 py-1.5 rounded-md text-[10px] uppercase font-bold tracking-widest transition-all {{ request('sort', 'size') === 'size' ? 'bg-white text-slate-950 shadow-sm ring-1 ring-white/20' : 'text-text-secondary hover:text-white hover:bg-white/10' }}">
                                {{ __('Size') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'deposits']) }}"
                                class="px-2.5 py-1.5 rounded-md text-[10px] uppercase font-bold tracking-widest transition-all {{ request('sort') === 'deposits' ? 'bg-white text-slate-950 shadow-sm ring-1 ring-white/20' : 'text-text-secondary hover:text-white hover:bg-white/10' }}">
                                {{ __('Deps') }}
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'payouts']) }}"
                                class="px-2.5 py-1.5 rounded-md text-[10px] uppercase font-bold tracking-widest transition-all {{ request('sort') === 'payouts' ? 'bg-white text-slate-950 shadow-sm ring-1 ring-white/20' : 'text-text-secondary hover:text-white hover:bg-white/10' }}">
                                {{ __('Payouts') }}
                            </a>
                        </div>
                    </div>

                    @if (count($top_referrers) > 0)
                        <div class="space-y-4">
                            @foreach ($top_referrers as $index => $top)
                                <a href="{{ request()->fullUrlWithQuery(['user_id' => $top->id]) }}"
                                    class="flex flex-col p-4 bg-white/5 border border-white/5 rounded-2xl group hover:bg-white/[0.08] hover:border-accent-primary/20 transition-all hover:translate-x-1 duration-300 {{ request('user_id') == $top->id ? 'border-accent-primary/50 bg-accent-primary/5' : '' }}">

                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div
                                                class="w-10 h-10 shrink-0 rounded-xl {{ $index === 0 ? 'bg-amber-500/20 text-amber-500 border-amber-500/30' : ($index === 1 ? 'bg-slate-300/20 text-slate-300 border-slate-300/30' : ($index === 2 ? 'bg-amber-700/20 text-amber-600 border-amber-700/30' : 'bg-white/5 text-text-secondary border-white/10')) }} flex items-center justify-center font-black text-sm border shadow-inner">
                                                #{{ $index + 1 }}
                                            </div>
                                            <div class="min-w-0">
                                                <p
                                                    class="text-white font-bold text-sm tracking-tight truncate group-hover:text-accent-primary transition-colors">
                                                    {{ $top->first_name }} {{ $top->last_name }}</p>
                                                <p class="text-text-secondary text-[10px] font-mono opacity-80 truncate">
                                                    {{ $top->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-2 mt-2 pt-3 border-t border-white/5">
                                        <div>
                                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-widest">
                                                {{ __('Size') }}</p>
                                            <p
                                                class="text-white font-black text-sm group-hover:text-accent-primary transition-colors">
                                                {{ $top->network_size }}</p>
                                        </div>
                                        <div>
                                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-widest">
                                                {{ __('Deps') }}</p>
                                            <p
                                                class="text-white font-black text-sm group-hover:text-emerald-400 transition-colors">
                                                {{ showAmount($top->network_deposits) }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-widest">
                                                {{ __('Pays') }}</p>
                                            <p class="text-accent-primary font-black text-sm">
                                                {{ showAmount($top->network_payouts) }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-sm font-medium text-text-secondary">{{ __('No referrals generated yet.') }}</p>
                        </div>
                    @endif

                    <div class="mt-8 pt-6 border-t border-white/5">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-bold text-white uppercase tracking-widest">{{ __('Tier Policy') }}</p>
                            <a href=""
                                class="text-[10px] font-bold text-accent-primary hover:text-white transition-colors">{{ __('Edit Settings') }}</a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($referral_bonus_percentage as $index => $percent)
                                <div
                                    class="px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg flex items-center gap-2">
                                    <span class="text-[10px] font-mono text-text-secondary">L{{ $index + 1 }}</span>
                                    <span class="text-xs font-black text-white">{{ $percent }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Viewing Area (Tree vs Global List) --}}
            <div class="lg:col-span-2 space-y-6">

                @if ($target_user)
                    {{-- Interactive Tree View --}}
                    <div
                        class="bg-secondary border border-white/5 rounded-3xl overflow-hidden shadow-xl flex flex-col min-h-[500px]">
                        <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between bg-white/[0.02]">
                            <div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.referrals.index') }}"
                                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-text-secondary hover:text-white hover:bg-white/10 transition-colors tooltip-trigger"
                                        title="{{ __('Back to Global') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                    </a>
                                    <h2 class="text-lg font-black text-white tracking-tight">
                                        {{ __('Affiliate Overview: ') }} <span
                                            class="text-accent-primary">{{ $target_user->username }}</span></h2>
                                </div>
                                <p class="text-text-secondary text-xs opacity-80 mt-1 ml-11">
                                    {{ __('Total Downline: :size | Deposits: :deps | Payouts: :pays', [
                                        'size' => $target_network_stats['size'],
                                        'deps' => showAmount($target_network_stats['deposits']),
                                        'pays' => showAmount($target_network_stats['payouts']),
                                    ]) }}
                                </p>
                            </div>
                        </div>

                        <div class="flex-1 p-8 overflow-x-auto overflow-y-auto max-h-[600px] custom-scrollbar">
                            @if (empty($referral_tree))
                                <div class="h-full flex flex-col items-center justify-center text-center py-12">
                                    <div
                                        class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center mb-6 text-text-secondary border border-white/10 shadow-inner group transition-all duration-500 hover:scale-110">
                                        <svg class="w-10 h-10 opacity-30 group-hover:opacity-100 transition-opacity text-accent-primary"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="text-white font-black text-xl mb-2">{{ __('Network Empty') }}</h4>
                                    <p class="text-text-secondary text-sm max-w-xs mx-auto mb-8 opacity-60">
                                        {{ __('This user currently has absolutely no downstream referrals in their network.') }}
                                    </p>
                                </div>
                            @else
                                <div class="referral-tree space-y-2 w-max min-w-full pr-24 pb-12">
                                    @foreach ($referral_tree as $node)
                                        @include(
                                            'templates.bento.blades.admin.referral-networks.partials.tree-item',
                                            [
                                                'node' => $node,
                                            ]
                                        )
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    {{-- Global Flat Referral History Table --}}
                    <div class="bg-secondary border border-white/5 rounded-3xl overflow-hidden shadow-xl">
                        <div
                            class="px-8 py-6 border-b border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/[0.02]">
                            <div>
                                <h2 class="text-lg font-black text-white tracking-tight">{{ __('Latest Global Invites') }}
                                </h2>
                                <p class="text-text-secondary text-xs opacity-60">
                                    {{ __('Timeline of users who registered via a referral link system-wide.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-accent-primary pulse"></span>
                                <span
                                    class="text-[10px] font-bold text-text-secondary uppercase tracking-widest">{{ __('Live Sync') }}</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto min-h-[400px]">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-white/5 border-b border-white/5">
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-text-secondary uppercase tracking-widest">
                                            {{ __('New Member') }}</th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-text-secondary uppercase tracking-widest">
                                            {{ __('Referred By') }}</th>
                                        <th
                                            class="px-8 py-5 text-[10px] font-black text-text-secondary uppercase tracking-widest">
                                            {{ __('Joined Date') }}</th>
                                        <th class="px-8 py-5 text-right"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse ($referrals as $row)
                                        <tr class="hover:bg-white/[0.02] transition-colors group">
                                            {{-- The New User --}}
                                            <td class="px-8 py-4">
                                                <div class="flex flex-col">
                                                    <a href="{{ route('admin.users.detail', $row->id) }}"
                                                        class="text-sm font-bold text-white hover:text-accent-primary transition-colors">
                                                        {{ $row->first_name }} {{ $row->last_name }}
                                                    </a>
                                                    <span
                                                        class="text-[10px] font-mono text-text-secondary">{{ $row->email }}</span>
                                                </div>
                                            </td>

                                            {{-- The Referrer --}}
                                            <td class="px-8 py-4">
                                                @if ($row->referrer)
                                                    <div class="flex items-center gap-3">
                                                        @if ($row->referrer->photo)
                                                            <img src="{{ asset('storage/profile/' . $row->referrer->photo) }}"
                                                                class="w-8 h-8 rounded-full border border-white/10 object-cover shrink-0">
                                                        @else
                                                            <div
                                                                class="w-8 h-8 rounded-full bg-white/5 border border-white/10 text-white flex items-center justify-center font-bold text-[10px] shrink-0">
                                                                {{ strtoupper(substr($row->referrer->first_name, 0, 1)) }}{{ strtoupper(substr($row->referrer->last_name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div class="flex flex-col">
                                                            <a href="?user_id={{ $row->referrer->id }}"
                                                                class="text-sm font-bold text-white hover:text-purple-400 transition-colors">
                                                                {{ $row->referrer->username }}
                                                            </a>
                                                            <span
                                                                class="text-[10px] font-mono text-text-secondary">{{ $row->referrer->referral_code }}</span>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span
                                                        class="text-xs text-text-secondary italic">{{ __('Unknown') }}</span>
                                                @endif
                                            </td>

                                            {{-- Joined Date --}}
                                            <td class="px-8 py-4">
                                                <p class="text-sm font-medium text-white">
                                                    {{ $row->created_at->format('M d, Y') }}</p>
                                                <p class="text-[10px] text-text-secondary">
                                                    {{ $row->created_at->format('h:i A') }}</p>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="px-8 py-4 text-right">
                                                <a href="{{ route('admin.users.detail', $row->id) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-text-secondary hover:text-white hover:bg-white/10 transition-colors tooltip-trigger"
                                                    title="{{ __('View User') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-8 py-12 text-center text-text-secondary">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div
                                                        class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                                                        <svg class="w-8 h-8 opacity-50" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1.5"
                                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm font-medium">{{ __('No referral history found.') }}
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($referrals->hasPages())
                            <div class="px-8 py-6 border-t border-white/5">
                                {{ $referrals->withQueryString()->links('templates.bento.blades.partials.pagination') }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
