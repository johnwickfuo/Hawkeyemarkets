@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Withdrawal Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Withdrawn') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ showAmount($stats['total_withdrawn']) }}</h4>
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
                            {{ __('Total Completed') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ showAmount($stats['total_completed']) }}</h4>
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
                        <h4 class="text-xl font-bold text-white">
                            {{ number_format($stats['pending_count']) }}
                            <span
                                class="text-sm font-medium text-amber-400/80 ml-1">({{ showAmount($stats['pending_amount']) }})</span>
                        </h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-red-500/20 rounded-lg text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Failed / Rejected') }}</p>
                        <h4 class="text-xl font-bold text-white">
                            {{ number_format($stats['failed_count']) }}
                            <span
                                class="text-sm font-medium text-red-400/80 ml-1">({{ showAmount($stats['failed_amount']) }})</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graph and Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            {{-- Trend Graph (2/3 width) --}}
            <div
                class="lg:col-span-2 relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute top-0 left-0 -mt-10 -ml-10 w-48 h-48 bg-accent-primary/10 rounded-full blur-3xl pointer-events-none z-0">
                </div>

                <div class="relative z-10 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                        <div>
                            <div id="graph-title" class="text-sm font-bold text-white tracking-wide">
                                {{ __('Withdrawal Growth Trend') }}
                            </div>
                            <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                                {{ __('Capital Withdrawn') }} · {{ getSetting('currency') }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 flex-wrap">
                            <div
                                class="flex items-center flex-wrap gap-1 bg-white/[0.03] border border-white/[0.06] rounded-lg p-1">
                                @foreach (['7d' => '7D', '30d' => '30D', '60d' => '60D', '90d' => '90D', '1y' => '1Y', 'ytd' => 'YTD'] as $key => $label)
                                    <button data-period="{{ $key }}"
                                        class="graph-period-btn cursor-pointer px-2.5 py-1 rounded-md text-[9px] font-bold uppercase tracking-wider transition-all
                                            {{ $key === '7d' ? 'bg-white/10 text-white' : 'text-slate-500 hover:text-slate-300' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div id="graph-legend" class="flex items-center gap-4 mb-4 flex-wrap"></div>

                    <div class="relative h-64">
                        <canvas id="withdrawalTrendChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Status Distribution (1/3 width) --}}
            <div
                class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>
                <div
                    class="absolute bottom-0 right-0 -mb-10 -mr-10 w-40 h-40 bg-accent-primary/15 rounded-full blur-2xl pointer-events-none z-0">
                </div>

                <div class="relative z-10 p-6 h-full flex flex-col">
                    <div class="mb-5">
                        <div class="text-sm font-bold text-white tracking-wide">{{ __('Withdrawal Status Distribution') }}
                        </div>
                        <p class="text-[9px] text-slate-500 uppercase tracking-widest font-mono mt-0.5">
                            {{ __('Count by Status') }}
                        </p>
                    </div>

                    <div class="relative flex-1 flex items-center justify-center p-4" style="min-height: 250px;">
                        <canvas id="statusDistributionChart" class="max-h-[220px]"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Withdrawals Table Section --}}
        <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative" id="withdrawals-wrapper">
            {{-- Loading Spinner Overlay --}}
            <div id="loading-spinner"
                class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-3 text-text-secondary font-medium">{{ __('Loading withdrawals...') }}</p>
            </div>

            {{-- Table Filter Header --}}
            <div class="p-6 border-b border-white/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    {{ __('Withdrawals History') }}
                </h3>

                {{-- Filters --}}
                <form id="filter-form" action="{{ route('admin.withdrawals.index') }}" method="GET"
                    class="flex flex-wrap gap-2 items-center">
                    @if (request('user_id'))
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                    @endif
                    <div class="relative">
                        <input type="text" name="search" placeholder="{{ __('Search user, trx ref...') }}"
                            value="{{ request('search') }}"
                            class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-base placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-full lg:w-64">
                    </div>

                    <div class="relative custom-dropdown" id="status-filter-dropdown">
                        <input type="hidden" name="status" id="status-input" value="{{ request('status', 'all') }}">
                        <button type="button"
                            class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[120px] dropdown-btn cursor-pointer">
                            <span class="selected-label">
                                @if (request('status') == 'pending')
                                    {{ __('Pending') }}
                                @elseif(request('status') == 'completed')
                                    {{ __('Completed') }}
                                @elseif(request('status') == 'failed')
                                    {{ __('Failed') }}
                                @else
                                    {{ __('All Status') }}
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div
                            class="absolute top-full right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-value="all">{{ __('All Status') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-value="pending">{{ __('Pending') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-value="completed">{{ __('Completed') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-value="failed">{{ __('Failed') }}</div>
                        </div>
                    </div>

                    <div class="relative custom-dropdown" id="export-dropdown">
                        <button type="button"
                            class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[120px] dropdown-btn cursor-pointer">
                            <span class="selected-label">{{ __('Export') }}</span>
                            <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div
                            class="absolute top-full right-0 mt-2 w-full lg:w-48 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-export="csv">CSV</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-export="sql">SQL</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors"
                                data-export="pdf">PDF</div>
                        </div>
                    </div>

                    <button type="submit"
                        class="p-2 bg-accent-primary/10 text-accent-primary rounded-lg hover:bg-accent-primary/20 transition-all cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto overflow-y-clip">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-white/[0.02]">
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('User') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('Method') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('Trx Ref') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('Amount') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('Fee') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                {{ __('Payable') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                {{ __('Status') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-right">
                                {{ __('Date') }}</th>
                            <th
                                class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-right">
                                {{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($withdrawals as $withdrawal)
                            <tr class="hover:bg-white/[0.01] transition-all group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($withdrawal->user->photo)
                                            <div
                                                class="w-8 h-8 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                                <img src="{{ asset('storage/profile/' . $withdrawal->user->photo) }}"
                                                    alt="{{ $withdrawal->user->username }}"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs shrink-0">
                                                {{ substr($withdrawal->user->username, 0, 2) }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('admin.users.detail', $withdrawal->user->id) }}"
                                                class="text-xs text-white font-bold hover:text-accent-primary transition-colors block cursor-pointer">{{ $withdrawal->user->username }}</a>
                                            <span
                                                class="text-[10px] text-text-secondary block mb-1.5">{{ $withdrawal->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs text-white font-medium block">{{ $withdrawal->withdrawalMethod ? $withdrawal->withdrawalMethod->name : 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs text-slate-300 font-mono tracking-wider bg-white/5 px-2 py-1 rounded">{{ $withdrawal->transaction_reference }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs text-white font-bold">{{ showAmount($withdrawal->amount) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs text-red-400 font-bold">-{{ showAmount($withdrawal->fee_amount) }}</span>
                                    <span
                                        class="text-[9px] text-text-secondary block mt-0.5">({{ rtrim(rtrim($withdrawal->fee_percent, '0'), '.') }}%)</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-xs text-emerald-400 font-bold">{{ showAmount($withdrawal->amount_payable) }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($withdrawal->status == 'pending')
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-tighter">{{ __('Pending') }}</span>
                                    @elseif($withdrawal->status == 'completed')
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-tighter">{{ __('Completed') }}</span>
                                    @elseif($withdrawal->status == 'failed')
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-tighter">{{ __('Failed') }}</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full bg-slate-500/10 text-slate-500 text-[10px] font-black uppercase tracking-tighter">{{ $withdrawal->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-[10px] text-white font-bold block mb-1">
                                        {{ $withdrawal->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="text-[9px] text-text-secondary/60">
                                        {{ $withdrawal->created_at->format('H:i') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.withdrawals.view', $withdrawal->id) }}"
                                            class="p-2 bg-blue-500/10 text-blue-400 rounded-lg hover:bg-blue-500/20 transition-all cursor-pointer"
                                            title="{{ __('View Withdrawal') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button type="button"
                                            class="edit-withdrawal-btn p-2 bg-accent-primary/10 text-accent-primary rounded-lg hover:bg-accent-primary/20 transition-all cursor-pointer"
                                            data-id="{{ $withdrawal->id }}" data-status="{{ $withdrawal->status }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button type="button"
                                            class="delete-withdrawal-btn p-2 bg-red-500/10 text-red-400 rounded-lg hover:bg-red-500/20 transition-all cursor-pointer"
                                            data-id="{{ $withdrawal->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-text-secondary text-sm italic">
                                    {{ __('No withdrawals found matching your criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($withdrawals->hasPages())
                <div class="px-6 py-4 border-t border-white/5 ajax-pagination">
                    {{ $withdrawals->links('templates.bento.blades.partials.pagination') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Status Modal --}}
    <div id="edit-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close"></div>

            <div
                class="relative w-full max-w-md transform rounded-3xl bg-secondary border border-white/10 p-8 text-left shadow-2xl transition-all">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Edit Status') }}</h3>
                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-mono">
                                {{ __('Update withdrawal status') }}</p>
                        </div>
                        <button type="button"
                            class="text-slate-500 hover:text-white transition-colors modal-close cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="edit-status-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="withdrawal_id" id="edit-withdrawal-id">

                        <div class="pb-20">
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 font-mono ml-1">
                                {{ __('Select New Status') }}
                            </label>
                            <div class="relative custom-dropdown" id="edit-status-dropdown" style="z-index: 50;">
                                <button type="button"
                                    class="dropdown-btn w-full flex items-center justify-between gap-3 px-4 py-3.5 rounded-xl bg-white/[0.03] border border-white/10 text-white text-sm focus:border-accent-primary/50 transition-all cursor-pointer">
                                    <span class="selected-label">{{ __('Select Status') }}</span>
                                    <svg class="w-4 h-4 text-slate-500 dropdown-icon transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <input type="hidden" name="status" id="edit-status-value">
                                <div
                                    class="dropdown-menu absolute z-[120] mt-2 w-full bg-slate-900/98 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl py-2 hidden animate-in fade-in slide-in-from-top-2 duration-200">
                                    @foreach (['pending', 'completed', 'failed'] as $st)
                                        <div class="dropdown-option px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors cursor-pointer capitalize"
                                            data-value="{{ $st }}">
                                            {{ ucfirst($st) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex gap-3">
                            <button type="button"
                                class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-primary/90 shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close"></div>

            <div
                class="relative w-full max-w-md transform rounded-3xl bg-secondary border border-white/10 p-8 text-left shadow-2xl transition-all">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-3xl">
                </div>

                <div class="relative z-10 text-center">
                    <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-white tracking-tight mb-2">{{ __('Confirm Deletion') }}</h3>
                    <p class="text-sm text-slate-400 mb-8 px-4">
                        {{ __('Are you sure you want to delete this withdrawal? This action cannot be undone.') }}
                    </p>

                    <div class="flex gap-3">
                        <input type="hidden" id="delete-withdrawal-id">
                        <button type="button"
                            class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" id="confirm-delete"
                            class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-bold hover:bg-red-600 shadow-lg shadow-red-500/20 transition-all cursor-pointer">
                            {{ __('Delete Forever') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Export Modal --}}
    <div id="export-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close cursor-pointer">
            </div>

            <div
                class="relative w-full max-w-md transform rounded-3xl bg-secondary border border-white/10 p-8 text-left shadow-2xl transition-all">
                <div
                    class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-3xl">
                </div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Export Settings') }}</h3>
                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-mono">
                                {{ __('Select columns to include') }}</p>
                        </div>
                        <button type="button"
                            class="text-slate-500 hover:text-white transition-colors modal-close cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4 font-mono ml-1">
                                {{ __('Select Columns') }}
                            </p>
                            <div class="grid grid-cols-2 gap-3" id="export-columns-container">
                                @php
                                    $columns = [
                                        'username' => ['label' => 'User', 'default' => true],
                                        'withdrawal_method_name' => ['label' => 'Method', 'default' => true],
                                        'amount' => ['label' => 'Amount', 'default' => true],
                                        'fee_amount' => ['label' => 'Fee', 'default' => true],
                                        'amount_payable' => ['label' => 'Payable', 'default' => true],
                                        'converted_amount' => ['label' => 'Converted', 'default' => false],
                                        'exchange_rate' => ['label' => 'Exchange Rate', 'default' => false],
                                        'transaction_reference' => ['label' => 'Trx Ref', 'default' => true],
                                        'status' => ['label' => 'Status', 'default' => true],
                                        'created_at' => ['label' => 'Date', 'default' => true],
                                    ];
                                @endphp
                                @foreach ($columns as $key => $col)
                                    <label
                                        class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/5 hover:border-accent-primary/50 transition-all cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" name="export_cols[]" value="{{ $key }}"
                                                {{ $col['default'] ? 'checked' : '' }}
                                                class="w-5 h-5 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                        </div>
                                        <span
                                            class="text-[11px] text-slate-400 group-hover:text-white transition-colors">{{ __($col['label']) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex gap-3">
                            <input type="hidden" id="pending-export-type" value="">
                            <button type="button"
                                class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" id="confirm-export"
                                class="flex-1 px-4 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-primary/90 shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">
                                {{ __('Generate Export') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            // ── Data from PHP ────────────────────────────────────────────────────────
            const GRAPH_DATA = @json($graph_data);
            const STATUS_CHART_DATA = @json($status_chart_data);

            // ── Shared Chart.js defaults ─────────────────────────────────────────────
            const CURRENCY = "{{ getSetting('currency') }}";
            Chart.defaults.color = 'rgba(148,163,184,0.7)';
            Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
            Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui';
            Chart.defaults.font.size = 10;

            const tooltipOptions = {
                backgroundColor: 'rgba(15,23,42,0.95)',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                padding: 10,
                titleFont: {
                    size: 10,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 10
                },
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null && context.parsed.y !== undefined) {
                            label += new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: CURRENCY
                            }).format(context.parsed.y);
                        } else if (context.parsed !== null && context.chart.config.type === 'doughnut') {
                            label += new Intl.NumberFormat('en-US', {
                                style: 'currency',
                                currency: CURRENCY
                            }).format(context.parsed);
                        }
                        return label;
                    }
                }
            };

            // ── Withdrawal Trend Chart ───────────────────────────────────────────────
            const trendCtx = document.getElementById('withdrawalTrendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
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
                        },
                        tooltip: tooltipOptions,
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255,255,255,0.03)'
                            },
                            ticks: {
                                maxTicksLimit: 8,
                                maxRotation: 0
                            },
                        },
                        y: {
                            grid: {
                                color: 'rgba(255,255,255,0.04)'
                            },
                            ticks: {
                                maxTicksLimit: 5
                            },
                            beginAtZero: true,
                        },
                    },
                },
            });

            // Status Colors Mapping
            const statusConfig = {
                'pending': {
                    color: '#f59e0b',
                    bg: 'rgba(245, 158, 11, 0.1)',
                    border: 'rgba(245, 158, 11, 0.3)',
                    label: '{{ __('Pending') }}'
                },
                'completed': {
                    color: '#10b981',
                    bg: 'rgba(16, 185, 129, 0.1)',
                    border: 'rgba(16, 185, 129, 0.3)',
                    label: '{{ __('Completed') }}'
                },
                'failed': {
                    color: '#ef4444',
                    bg: 'rgba(239, 68, 68, 0.1)',
                    border: 'rgba(239, 68, 68, 0.3)',
                    label: '{{ __('Failed') }}'
                }
            };

            function updateTrendChart(period) {
                if (!GRAPH_DATA[period]) return;

                const periodData = GRAPH_DATA[period];

                // Collect all unique dates from all statuses to form the x-axis labels
                let allLabels = new Set();
                Object.values(periodData).forEach(statusData => {
                    statusData.labels.forEach(l => allLabels.add(l));
                });

                let sortedLabels = Array.from(allLabels).sort();

                // If there's no data at all, just provide a dummy label so the chart doesn't break
                if (sortedLabels.length === 0) {
                    sortedLabels = [new Date().toISOString().split('T')[0]];
                }

                const datasets = Object.keys(statusConfig).map(status => {
                    const cfg = statusConfig[status];
                    const dataObj = periodData[status];

                    // Map the data to the sorted labels
                    const mappedData = sortedLabels.map(label => {
                        const idx = dataObj ? dataObj.labels.indexOf(label) : -1;
                        return idx !== -1 ? dataObj.data[idx] : 0;
                    });

                    return {
                        label: cfg.label,
                        data: mappedData,
                        borderColor: cfg.color,
                        backgroundColor: cfg.bg,
                        borderWidth: 2,
                        pointBackgroundColor: '#0F172A',
                        pointBorderColor: cfg.color,
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: true,
                        tension: 0.4
                    };
                });

                // Format labels for display (e.g. "Mar 15")
                const displayLabels = sortedLabels.map(dateStr => {
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                });

                trendChart.data.labels = displayLabels;
                trendChart.data.datasets = datasets;
                trendChart.update();

                // Also update the custom legend HTML
                updateGraphLegend(datasets);
            }

            function updateGraphLegend(datasets) {
                const legendContainer = document.getElementById('graph-legend');
                legendContainer.innerHTML = '';

                datasets.forEach(ds => {
                    const item = document.createElement('div');
                    item.className =
                        'flex items-center gap-2 text-[10px] font-bold tracking-wider uppercase text-slate-400';
                    item.innerHTML = `
                        <span class="w-2 h-2 rounded-full" style="background-color: ${ds.borderColor}"></span>
                        ${ds.label}
                    `;
                    legendContainer.appendChild(item);
                });
            }

            // Initialize Graph
            updateTrendChart('7d');

            // ── Status Distribution Chart ──────────────────────────────────────────────
            const statusCtx = document.getElementById('statusDistributionChart').getContext('2d');

            // Re-order and map the chart data according to our statusConfig
            const doughnutLabels = [];
            const doughnutData = [];
            const doughnutAmounts = [];
            const doughnutColors = [];

            Object.keys(statusConfig).forEach(key => {
                const foundItem = STATUS_CHART_DATA.find(item => item.status.toLowerCase() === key);
                doughnutLabels.push(statusConfig[key].label);
                doughnutData.push(foundItem ? foundItem.count : 0);
                doughnutAmounts.push(foundItem ? foundItem.amount : 0);
                doughnutColors.push(statusConfig[key].color);
            });

            const statusChart = new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: doughnutLabels,
                    datasets: [{
                        data: doughnutData,
                        amounts: doughnutAmounts, // Store amounts directly in dataset
                        backgroundColor: doughnutColors,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 11
                                },
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            ...tooltipOptions,
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }

                                    const count = context.parsed;
                                    const amount = context.dataset.amounts[context.dataIndex];

                                    const formattedAmount = new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: CURRENCY
                                    }).format(amount);

                                    label += `${count} (${formattedAmount})`;
                                    return label;
                                }
                            }
                        }
                    }
                }
            });


            // Handle Graph Period Switching
            $('.graph-period-btn').on('click', function() {
                $('.graph-period-btn').removeClass('bg-white/10 text-white').addClass(
                    'text-slate-500 hover:text-slate-300');
                $(this).removeClass('text-slate-500 hover:text-slate-300').addClass(
                    'bg-white/10 text-white');

                const period = $(this).data('period');
                updateTrendChart(period);
            });


            // ── Custom Dropdowns functionality ──────────────────────────────────────
            $(document).on('click', '.dropdown-btn', function(e) {
                e.stopPropagation();
                // Close others
                $('.dropdown-menu').not($(this).siblings('.dropdown-menu')).addClass('hidden');
                $('.dropdown-icon').not($(this).find('.dropdown-icon')).removeClass('rotate-180');

                // Toggle current
                const menu = $(this).siblings('.dropdown-menu');
                const icon = $(this).find('.dropdown-icon');

                menu.toggleClass('hidden');
                icon.toggleClass('rotate-180');
            });

            // Close dropdowns on outside click
            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            // Status Filter Select
            $(document).on('click', '#status-filter-dropdown .dropdown-option', function() {
                const val = $(this).data('value');
                const label = $(this).text();

                $('#status-input').val(val);
                $('#status-filter-dropdown .selected-label').text(label);

                // Try to load via AJAX filtering instead of full page submit
                $('#filter-form').submit();
            });

            // Export Actions
            $(document).on('click', '#export-dropdown .dropdown-option', function() {
                const exportType = $(this).data('export');
                $('#pending-export-type').val(exportType);
                openModal('export-modal');
            });

            $('#confirm-export').on('click', function() {
                const exportType = $('#pending-export-type').val();

                // Get selected columns
                const selectedCols = [];
                $('input[name="export_cols[]"]:checked').each(function() {
                    selectedCols.push($(this).val());
                });

                if (selectedCols.length === 0) {
                    toastNotification('{{ __('Please select at least one column to export.') }}', 'error');
                    return;
                }

                // Construct export URL
                const baseUrl = $('#filter-form').attr('action');
                let params = new URLSearchParams(window.location.search);
                params.set('export', exportType);
                params.set('columns', selectedCols.join(','));

                closeModal('export-modal');
                window.location.href = baseUrl + '?' + params.toString();
            });

            // ── Ajax Pagination  ────────────────────────
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                loadTable(url);
            });

            // AJAX Filtering
            $(document).on('submit', '#filter-form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action') + '?' + $(this).serialize();
                loadTable(url);
            });

            function loadTable(url) {
                $('#loading-spinner').removeClass('hidden').addClass('flex');
                $('#withdrawals-wrapper .overflow-x-auto').css('opacity', '0.5');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#withdrawals-wrapper').html($(data).find('#withdrawals-wrapper').html());
                        window.history.pushState({}, '', url);
                    },
                    error: function() {
                        toastNotification('{{ __('Error loading content.') }}', 'error');
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                        $('#withdrawals-wrapper .overflow-x-auto').css('opacity', '1');
                    }
                });
            }

            // ── Modals Handling ──────────────────────────────────────────────────────
            function openModal(id) {
                const $modal = $(`#${id}`);
                $modal.removeClass('hidden');
                // Give browser time to remove hidden before triggering transitions
                setTimeout(() => {
                    $modal.find('.transform').removeClass('scale-95 opacity-0').addClass(
                        'scale-100 opacity-100');
                    $modal.find('.fixed.inset-0.bg-slate-950\\/80').removeClass('opacity-0')
                        .addClass('opacity-100');
                }, 10);
            }

            function closeModal(id) {
                const $modal = $(`#${id}`);
                $modal.find('.transform').removeClass('scale-100 opacity-100').addClass(
                    'scale-95 opacity-0');
                $modal.find('.fixed.inset-0.bg-slate-950\\/80').removeClass('opacity-100').addClass(
                    'opacity-0');
                setTimeout(() => {
                    $modal.addClass('hidden');
                }, 300);
            }

            $('.modal-close').on('click', function() {
                const modalId = $(this).closest('.fixed.inset-0.z-\\[100\\]').attr('id');
                closeModal(modalId);
            });


            // ── Edit Status ─────────────────────────────────────────────────────────
            $(document).on('click', '.edit-withdrawal-btn', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');

                $('#edit-withdrawal-id').val(id);
                $('#edit-status-value').val(status);

                // Set the correct label based on current status
                let statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
                $('#edit-status-dropdown .selected-label').text(statusLabel);

                openModal('edit-modal');
            });

            // Edit Status Dropdown Select
            $('#edit-status-dropdown .dropdown-option').on('click', function() {
                const val = $(this).data('value');
                const label = $(this).text().trim();

                $('#edit-status-value').val(val);
                $('#edit-status-dropdown .selected-label').text(label);
            });

            $('#edit-status-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit-withdrawal-id').val();
                const status = $('#edit-status-value').val();

                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.text();
                $submitBtn.text('{{ __('Saving...') }}').prop('disabled', true);

                $.ajax({
                    url: `{{ url('admin/withdrawals/edit') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(res) {
                        if (res.success || res.status === 'success') {
                            closeModal('edit-modal');
                            toastNotification(res.message ||
                                'Withdrawal status updated successfully', 'success');
                            $submitBtn.text(originalText).prop('disabled', false);
                            loadTable(window.location.href);
                        } else {
                            toastNotification(res.message || 'An error occurred', 'error');
                            $submitBtn.text(originalText).prop('disabled', false);
                        }
                    },
                    error: function(err) {
                        let error = err.responseJSON?.message || 'An error occurred';
                        toastNotification(error, 'error');
                        $submitBtn.text(originalText).prop('disabled', false);
                    }
                });
            });

            // ── Delete Withdrawal ────────────────────────────────────────────────────────
            $(document).on('click', '.delete-withdrawal-btn', function() {
                const id = $(this).data('id');
                $('#delete-withdrawal-id').val(id);
                openModal('delete-modal');
            });

            $('#confirm-delete').on('click', function() {
                const id = $('#delete-withdrawal-id').val();
                const $btn = $(this);
                const originalText = $btn.text();

                $btn.text('{{ __('Deleting...') }}').prop('disabled', true);

                $.ajax({
                    url: `{{ url('admin/withdrawals/delete') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success || res.status === 'success') {
                            closeModal('delete-modal');
                            toastNotification(res.message || 'Withdrawal deleted successfully',
                                'success');
                            $btn.text(originalText).prop('disabled', false);
                            loadTable(window.location.href);
                        } else {
                            toastNotification(res.message || 'An error occurred', 'error');
                            $btn.text(originalText).prop('disabled', false);
                        }
                    },
                    error: function(err) {
                        let error = err.responseJSON?.message || 'An error occurred';
                        toastNotification(error, 'error');
                        $btn.text(originalText).prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
