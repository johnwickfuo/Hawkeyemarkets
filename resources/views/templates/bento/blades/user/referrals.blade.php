@extends('templates.bento.blades.layouts.user')

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
                        {{ __('Referral Program') }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black text-white leading-[1.1] mb-6 tracking-tighter">
                        {{ __('Invite Friends &') }} <br>
                        <span class="text-accent-primary decoration-accent-primary/20 underline underline-offset-8">
                            {{ __('Earn Multi-Level Perks') }} </span>
                    </h1>
                    <p class="text-text-secondary text-base md:text-lg leading-relaxed mb-8 opacity-80">
                        {{ __('Expand your network and earn passive income across :levels levels of referrals. Every successful investment by your network credits your account instantly.', ['levels' => $total_levels]) }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="relative w-full max-w-md group">
                            <input type="text" value="{{ $referral_link }}" id="referralLink" readonly
                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm text-white font-mono focus:outline-none focus:ring-2 focus:ring-accent-primary/50 transition-all cursor-default">
                            <button onclick="copyReferralLink()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-accent-primary hover:bg-accent-primary-hover text-white px-6 py-2.5 rounded-xl text-xs font-bold transition-all active:scale-95 shadow-lg shadow-accent-primary/20 cursor-pointer">
                                {{ __('Copy Link') }}
                            </button>
                        </div>
                    </div>

                    {{-- Social Sharing --}}
                    <div class="flex items-center gap-4 mt-8 justify-center lg:justify-start">
                        <p class="text-text-secondary text-[10px] font-bold uppercase tracking-widest mr-2">
                            {{ __('Share via') }}</p>
                        <a href="https://wa.me/?text={{ urlencode(__('Join me on :site_name ! Use my link to get started: :ref_link', ['site_name' => getSetting('name'), 'ref_link' => $referral_link])) }}"
                            target="_blank"
                            class="p-2.5 bg-white/5 hover:bg-[#25D366]/10 border border-white/5 hover:border-[#25D366]/20 rounded-xl text-text-secondary hover:text-[#25D366] transition-all cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.414 0 .004 5.411.002 12.048c0 2.12.54 4.19 1.566 6.055L0 24l6.101-1.599a11.83 11.83 0 005.946 1.59h.005c6.634 0 12.043-5.411 12.046-12.048a11.811 11.811 0 00-3.536-8.509" />
                            </svg>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode($referral_link) }}&text={{ urlencode(__('Join me on :site_name !', ['site_name' => getSetting('name')])) }}"
                            target="_blank"
                            class="p-2.5 bg-white/5 hover:bg-[#0088cc]/10 border border-white/5 hover:border-[#0088cc]/20 rounded-xl text-text-secondary hover:text-[#0088cc] transition-all cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11.944 0C5.346 0 0 5.346 0 11.944c0 6.598 5.346 11.944 11.944 11.944 6.598 0 11.944-5.346 11.944-11.944C23.888 5.346 18.542 0 11.944 0zm5.83 8.356c-.17 1.787-.922 6.22-1.305 8.267-.162.866-.481 1.155-.79 1.183-.67.062-1.178-.442-1.828-.868-1.017-.667-1.592-1.082-2.578-1.731-1.14-.75-.401-1.163.249-1.836.17-.176 3.125-2.867 3.181-3.102.007-.03.012-.138-.052-.196-.065-.057-.16-.038-.228-.022-.1-.02-.03-.217 1.62-.68.648-2.316.326-5.01-.225-5.92-.007-.013-.017-.035-.034-.05-.052-.047-.13-.047-.13-.047s-.08-.002-.224.038c-1.343.376-2.583 1.258-3.703 2.05-1.12.793-2.18 1.68-3.141 2.51-.9.778-1.631 1.554-2.193 2.196-.4.457-.75.875-1.05 1.24-.26.316-.32.486-.337.601a.91.91 0 00.176.622c.118.146.337.306.66.44.368.156.883.332 1.487.49 1.233.324 2.82.723 4.102 1.05 1.282.327 2.22.463 2.812.28 1.03-.318 1.649-.699 1.859-.9 2.073-1.986 4.146-3.972 6.219-5.958" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode(__('Start trading with my referral link: :ref_link', ['ref_link' => $referral_link])) }}"
                            target="_blank"
                            class="p-2.5 bg-white/5 hover:bg-[#1DA1F2]/10 border border-white/5 hover:border-[#1DA1F2]/20 rounded-xl text-text-secondary hover:text-[#1DA1F2] transition-all cursor-pointer">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Visual Element (illustration or glass box) --}}
                <div class="hidden lg:block relative group">
                    <div
                        class="absolute inset-0 bg-accent-primary rounded-3xl blur-3xl opacity-20 group-hover:opacity-30 transition-opacity">
                    </div>
                    <div
                        class="relative w-80 h-96 bg-white/5 backdrop-blur-3xl border border-white/10 rounded-3xl p-8 flex flex-col justify-center items-center shadow-2xl">
                        <div
                            class="w-24 h-24 bg-accent-primary/20 rounded-full flex items-center justify-center mb-8 shadow-inner shadow-accent-primary/50 border border-accent-primary/20">
                            <svg class="w-12 h-12 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-center space-y-4">
                            <h3 class="text-white font-black text-2xl tracking-tighter">{{ __('Build Your Empire') }}</h3>
                            <p class="text-text-secondary text-sm leading-relaxed">
                                {{ __('The bigger your network, the higher your reward multipliers.') }}</p>
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
                    {{ __('Referral Earnings') }}</p>
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-3xl font-black text-white decoration-accent-primary/20 hover:text-accent-primary transition-colors cursor-default">
                        {{ number_format($referral_earnings, 2) }}
                    </span>
                    <span class="text-xs font-bold text-accent-primary">{{ getSetting('currency') }}</span>
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
                    {{ __('Direct Referrals') }}</p>
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-3xl font-black text-white transition-colors cursor-default group-hover:text-purple-500">
                        {{ $referrals->total() }}
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
                    {{ __('Total Network') }}</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-black text-white transition-colors cursor-default group-hover:text-blue-500">
                        {{ $network_count }}
                    </span>
                    <span class="text-xs font-bold text-text-secondary">{{ __('Users') }}</span>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-2xl p-6 hover:border-emerald-500/30 transition-all group overflow-hidden relative">
                <div
                    class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-emerald-500">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z">
                        </path>
                    </svg>
                </div>
                <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-3">
                    {{ __('Active Levels') }}</p>
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-3xl font-black text-white transition-colors cursor-default group-hover:text-emerald-500">
                        {{ $total_levels }}
                    </span>
                    <span class="text-xs font-bold text-text-secondary">{{ __('Tiers') }}</span>
                </div>
            </div>
        </div>

        {{-- Referral Network Visualization --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Tree View --}}
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="bg-secondary border border-white/5 rounded-3xl overflow-hidden min-h-[500px] flex flex-col shadow-xl">
                    <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between bg-white/[0.02]">
                        <div>
                            <h2 class="text-lg font-black text-white tracking-tight">{{ __('Your Network Tree') }}</h2>
                            <p class="text-text-secondary text-xs opacity-60">
                                {{ __('Visualize your multi-level referral architecture') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-accent-primary pulse"></span>
                            <span
                                class="text-[10px] font-bold text-text-secondary uppercase tracking-widest">{{ __('Live Sync') }}</span>
                        </div>
                    </div>

                    <div class="flex-1 p-8 overflow-y-auto max-h-[600px] custom-scrollbar">
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
                                    {{ __('Start sharing your unique link to build your multi-level earning network today.') }}
                                </p>
                                <button onclick="copyReferralLink()"
                                    class="px-10 py-3.5 bg-accent-primary hover:bg-accent-primary-hover text-white font-bold rounded-2xl transition-all shadow-xl shadow-accent-primary/20 active:scale-95 cursor-pointer">
                                    {{ __('Copy My Link') }}
                                </button>
                            </div>
                        @else
                            <div class="referral-tree space-y-4">
                                @foreach ($referral_tree as $node)
                                    @include('templates.bento.blades.partials.referral-tree-item', [
                                        'node' => $node,
                                    ])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Bonus Policy --}}
            <div class="space-y-6">
                <div class="bg-secondary border border-white/5 rounded-3xl p-8 sticky top-24 shadow-xl">
                    <h2 class="text-lg font-black text-white tracking-tight mb-8">{{ __('Commission Breakdown') }}</h2>
                    <div class="space-y-6">
                        @foreach ($referral_bonus_percentage as $index => $percent)
                            <div
                                class="flex items-center justify-between p-4 bg-white/5 border border-white/5 rounded-2xl group hover:bg-white/[0.08] transition-all hover:-translate-x-1 duration-300">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs border border-accent-primary/20 group-hover:scale-110 transition-all">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-sm tracking-tight">{{ __('Level') }}
                                            {{ $index + 1 }}</p>
                                        <p class="text-text-secondary text-[10px] uppercase font-medium opacity-60">
                                            {{ __('Generation') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-accent-primary font-black text-xl tracking-tighter">
                                        {{ $percent }}%</p>
                                    <p class="text-text-secondary text-[10px] uppercase font-bold tracking-widest">
                                        {{ __('Reward') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tips Card --}}
                    <div
                        class="mt-10 p-6 bg-accent-primary/5 border border-accent-primary/10 rounded-2xl relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -top-4 w-16 h-16 bg-accent-primary/20 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <div class="relative z-10">
                            <h4
                                class="text-accent-primary font-black text-xs uppercase tracking-widest mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Pro Tip') }}
                            </h4>
                            <p class="text-white text-xs leading-relaxed opacity-80">
                                {{ __('Help your Level 1 referrals grow their network. When they earn, you earn from their Level 1 (your Level 2) too!') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Referral Table --}}
        <div class="bg-secondary border border-white/5 rounded-3xl overflow-hidden shadow-xl">
            <div
                class="px-8 py-6 border-b border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/[0.02]">
                <div>
                    <h2 class="text-lg font-black text-white tracking-tight">{{ __('Direct Referrals List') }}</h2>
                    <p class="text-text-secondary text-xs opacity-60">
                        {{ __('Manage and track your primary network performance') }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-white/5 border-b border-white/5 text-left">
                            <th class="px-8 py-5 text-xs font-black text-text-secondary uppercase tracking-widest">
                                {{ __('Member') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-text-secondary uppercase tracking-widest">
                                {{ __('Status') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-text-secondary uppercase tracking-widest">
                                {{ __('Referral Code') }}</th>
                            <th class="px-8 py-5 text-xs font-black text-text-secondary uppercase tracking-widest">
                                {{ __('Joined') }}</th>
                            <th class="px-8 py-5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach ($referrals as $row)
                            <tr class="hover:bg-white/5 transition-all duration-300 group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center text-white font-bold text-xs ring-1 ring-white/10 group-hover:ring-accent-primary group-hover:bg-accent-primary/10 transition-all">
                                            {{ strtoupper(substr($row->first_name, 0, 1)) }}{{ strtoupper(substr($row->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p
                                                class="text-white font-bold text-sm tracking-tight group-hover:text-accent-primary transition-colors cursor-default">
                                                {{ $row->first_name }} {{ $row->last_name }}
                                            </p>
                                            @php
                                                $emailParts = explode('@', $row->email);
                                                $maskedEmail =
                                                    substr($emailParts[0], 0, 2) . '***@' . ($emailParts[1] ?? '');
                                            @endphp
                                            <p class="text-text-secondary text-[10px] font-mono opacity-50">
                                                {{ $maskedEmail }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ __('Active') }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <p
                                        class="text-white font-mono text-xs font-bold bg-white/5 py-1 px-3 rounded-lg border border-white/5 inline-block">
                                        {{ $row->referral_code }}</p>
                                </td>
                                <td class="px-8 py-5 text-text-secondary text-sm font-medium">
                                    {{ $row->created_at->format('M d, Y') }}
                                    <p class="text-[10px] opacity-40">{{ $row->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-text-secondary group-hover:text-accent-primary group-hover:bg-accent-primary/10 transition-all cursor-not-allowed border border-transparent group-hover:border-accent-primary/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($referrals->hasPages())
                <div class="px-8 py-6 border-t border-white/5">
                    {{ $referrals->links('templates.bento.blades.partials.pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function copyReferralLink() {
            var copyText = document.getElementById("referralLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            // Toast feedback
            Toast.fire({
                icon: 'success',
                title: '{{ __('Referral link copied to clipboard') }}'
            });
        }

        // Custom tree interaction
        document.addEventListener('DOMContentLoaded', function() {
            const togglers = document.querySelectorAll('.tree-toggler');
            togglers.forEach(toggler => {
                toggler.addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    const children = document.getElementById(targetId);
                    const icon = this.querySelector('svg');

                    if (children) {
                        children.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                });
            });
        });
    </script>
@endsection
