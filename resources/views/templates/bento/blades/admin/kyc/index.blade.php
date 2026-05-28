@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('kyc_module'))
            <div
                class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px] bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                    {{ __('KYC module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('KYC module is disabled. Please enable the KYC module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        @if (moduleEnabled('kyc_module'))
            {{-- KYC Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Total Submissions') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($totalSubmissions) }}</h4>
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
                                {{ __('Approved') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($approvedCount) }}</h4>
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
                            <h4 class="text-xl font-bold text-white">{{ number_format($pendingCount) }}</h4>
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
                                {{ __('Rejected') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($rejectedCount) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status Distribution Chart --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div
                    class="relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors lg:col-span-1">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-0 right-0 -mb-10 -mr-10 w-40 h-40 bg-accent-primary/15 rounded-full blur-2xl pointer-events-none z-0">
                    </div>

                    <div class="relative z-10 p-6 h-full flex flex-col">
                        <div class="mb-5">
                            <div class="text-sm font-bold text-white tracking-wide">{{ __('KYC Status Distribution') }}
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

                <div
                    class="lg:col-span-2 relative bg-secondary border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors p-6 flex items-center justify-center">
                    <!-- Decorative element or banner -->
                    <div class="text-center">
                        <svg class="w-16 h-16 text-white/5 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                            </path>
                        </svg>
                        <h3 class="text-white font-bold text-lg mb-2">{{ __('Verification Process Manager') }}</h3>
                        <p class="text-text-secondary text-sm max-w-sm mx-auto">
                            {{ __('Review identity documents, verify proof of addresses, and manage compliance from this central dashboard.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- KYC Table Section --}}
            <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative" id="kyc-wrapper">
                {{-- Loading Spinner Overlay --}}
                <div id="loading-spinner"
                    class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                    <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin">
                    </div>
                    <p class="mt-3 text-text-secondary font-medium">{{ __('Loading KYC records...') }}</p>
                </div>

                {{-- Table Filter Header --}}
                <div class="p-6 border-b border-white/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                            </path>
                        </svg>
                        {{ __('KYC Submissions') }}
                    </h3>

                    {{-- Filters --}}
                    <form id="filter-form" action="{{ route('admin.kyc.index') }}" method="GET"
                        class="flex flex-wrap gap-2 items-center">
                        @if (request('user_id'))
                            <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        @endif
                        <div class="relative">
                            <input type="text" name="search" placeholder="{{ __('Search user, doc type...') }}"
                                value="{{ request('search') }}"
                                class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-base placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-full lg:w-64">
                        </div>

                        <div class="relative custom-dropdown" id="status-filter-dropdown">
                            <input type="hidden" name="status" id="status-input"
                                value="{{ request('status', 'all') }}">
                            <button type="button"
                                class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[120px] dropdown-btn cursor-pointer">
                                <span class="selected-label">
                                    @if (request('status') == 'pending')
                                        {{ __('Pending') }}
                                    @elseif(request('status') == 'approved')
                                        {{ __('Approved') }}
                                    @elseif(request('status') == 'rejected')
                                        {{ __('Rejected') }}
                                    @else
                                        {{ __('All Status') }}
                                    @endif
                                </span>
                                <svg class="w-4 h-4 text-text-secondary pointer-events-none transition-transform duration-200"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <ul
                                class="dropdown-menu absolute z-50 mt-1 w-full bg-[#1E293B] border border-white/10 rounded-lg shadow-xl hidden opacity-0 transition-opacity duration-200 overflow-hidden text-sm">
                                <li class="dropdown-option px-4 py-2 hover:bg-white/5 text-white cursor-pointer transition-colors"
                                    data-value="all">{{ __('All Status') }}</li>
                                <li class="dropdown-option px-4 py-2 hover:bg-white/5 text-white cursor-pointer transition-colors"
                                    data-value="pending">{{ __('Pending') }}</li>
                                <li class="dropdown-option px-4 py-2 hover:bg-white/5 text-white cursor-pointer transition-colors"
                                    data-value="approved">{{ __('Approved') }}</li>
                                <li class="dropdown-option px-4 py-2 hover:bg-white/5 text-white cursor-pointer transition-colors"
                                    data-value="rejected">{{ __('Rejected') }}</li>
                            </ul>
                        </div>
                    </form>
                </div>

                {{-- Table Wrapper --}}
                <div class="overflow-x-auto min-h-[300px]">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/[0.02]">
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest">
                                    {{ __('User Details') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                    {{ __('Document Type') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                    {{ __('Submitted Date') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-center">
                                    {{ __('Status') }}</th>
                                <th
                                    class="px-6 py-4 border-b border-white/5 text-[10px] font-black uppercase text-text-secondary tracking-widest text-right">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($kycs as $kyc)
                                <tr class="hover:bg-white/[0.01] transition-all group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($kyc->user->photo)
                                                <div
                                                    class="w-10 h-10 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                                    <img src="{{ asset('storage/profile/' . $kyc->user->photo) }}"
                                                        alt="{{ $kyc->user->username }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs shrink-0 border border-accent-primary/20">
                                                    {{ substr($kyc->user->username, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.users.detail', $kyc->user->id) }}"
                                                    class="text-sm text-white font-bold hover:text-accent-primary transition-colors block cursor-pointer">{{ $kyc->user->first_name }}
                                                    {{ $kyc->user->last_name }}</a>
                                                <span
                                                    class="text-[10px] text-text-secondary uppercase tracking-widest block">{{ '@' . $kyc->user->username }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-white/5 border border-white/10">
                                            <svg class="w-4 h-4 text-text-secondary" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-xs font-bold text-white uppercase">{{ __(str_replace('_', ' ', $kyc->document_type)) }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="text-xs text-white font-medium">
                                            {{ $kyc->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[10px] text-text-secondary">
                                            {{ $kyc->created_at->format('h:i A') }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($kyc->status === 'approved')
                                            <div
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-wider">{{ __('Approved') }}</span>
                                            </div>
                                        @elseif($kyc->status === 'pending')
                                            <div
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-wider">{{ __('Pending') }}</span>
                                            </div>
                                        @elseif($kyc->status === 'rejected')
                                            <div
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-500/10 border border-red-500/20 text-red-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-wider">{{ __('Rejected') }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.kyc.view', $kyc->id) }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 text-text-secondary hover:text-white hover:bg-white/10 hover:border-white/20 transition-all cursor-pointer"
                                                title="{{ __('View Details') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <button type="button" onclick="confirmDelete('{{ $kyc->id }}')"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 text-text-secondary hover:text-red-400 hover:bg-red-500/10 hover:border-red-500/20 transition-all cursor-pointer"
                                                title="{{ __('Delete Record') }}">
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
                                    <td colspan="100%" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-text-secondary">
                                            <div
                                                class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium">{{ __('No KYC records found') }}</p>
                                            <p class="text-xs opacity-70 mt-1">
                                                {{ __('Try adjusting your search or filters') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($kycs->hasPages())
                    <div class="p-6 border-t border-white/5 bg-black/20 ajax-pagination">
                        {{ $kycs->withQueryString()->links('templates.bento.blades.partials.pagination') }}
                    </div>
                @endif
            </div>
        @endif
    </div>


    {{-- MODALS --}}

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-primary-dark/80 backdrop-blur-sm z-40 modal-overlay cursor-pointer"
            onclick="closeDeleteModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                class="relative bg-secondary-dark border border-white/10 rounded-2xl shadow-2xl text-left overflow-hidden transform transition-all sm:max-w-md w-full z-50 animate-modal-enter">

                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeDeleteModal()"
                        class="text-text-secondary hover:text-white focus:outline-none transition-colors cursor-pointer">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-8 sm:flex sm:items-start text-center sm:text-left">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-500/20 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 sm:mt-0 sm:ml-4 flex-1">
                        <h3 class="text-lg font-bold text-white mb-2" id="modal-title">
                            {{ __('Delete KYC Record') }}
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-text-secondary">
                                {{ __('Are you sure you want to delete this KYC record? This action cannot be undone and will permanently remove the record from the database.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-black/20 px-6 py-4 border-t border-white/5 flex flex-col sm:flex-row-reverse sm:gap-3">
                    <button type="button" id="confirmDeleteBtn"
                        class="w-full inline-flex justify-center items-center rounded-xl bg-red-600 hover:bg-red-500 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition-all focus:outline-none sm:w-auto relative overflow-hidden group">
                        <span class="btn-text">{{ __('Delete Record') }}</span>
                        <div class="btn-loader hidden absolute inset-0 flex items-center justify-center bg-red-600">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-xl bg-white/5 border border-white/10 px-4 py-2 text-sm font-medium text-white hover:bg-white/10 transition-all focus:outline-none sm:mt-0 sm:w-auto">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Javascript for Interactivity --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $(document).ready(function() {
                // === Form and Filters Logic ===
                const $filterForm = $('#filter-form');
                const $statusInput = $('#status-input');
                const $searchInput = $filterForm.find('input[name="search"]');

                // Add clear button to search
                if ($searchInput.val()) {
                    const clearBtnHtml = `
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-text-secondary hover:text-white p-1 rounded-md bg-white/5 border border-white/10 cursor-pointer search-clear-btn">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>`;

                    $searchInput.after(clearBtnHtml);
                    $searchInput.addClass('pr-10');

                    $('.search-clear-btn').on('click', function() {
                        $searchInput.val('');
                        loadTable();
                    });
                }

                // Dropdown logic
                const $statusDropdown = $('#status-filter-dropdown');
                const $statusBtn = $statusDropdown.find('.dropdown-btn');
                const $statusMenu = $statusDropdown.find('.dropdown-menu');
                const $statusOptions = $statusDropdown.find('.dropdown-option');
                const $selectedLabel = $statusDropdown.find('.selected-label');

                $statusBtn.on('click', function(e) {
                    e.stopPropagation();
                    const isExpanded = $statusMenu.hasClass('opacity-100');

                    // Close all other dropdowns
                    $('.dropdown-menu').not($statusMenu).each(function() {
                        const $menu = $(this);
                        if (!$menu.hasClass('hidden')) {
                            $menu.removeClass('opacity-100 translate-y-0');
                            $menu.addClass('opacity-0 translate-y-2');
                            setTimeout(() => $menu.addClass('hidden'), 200);
                            const $btn = $menu.closest('.custom-dropdown').find('.dropdown-btn svg');
                            if ($btn.length) $btn.removeClass('rotate-180');
                        }
                    });

                    if (!isExpanded) {
                        $statusMenu.removeClass('hidden');
                        // Trigger reflow
                        $statusMenu[0].offsetHeight;
                        $statusMenu.removeClass('opacity-0 translate-y-2');
                        $statusMenu.addClass('opacity-100 translate-y-0');
                        $statusBtn.find('svg').addClass('rotate-180');
                    } else {
                        $statusMenu.removeClass('opacity-100 translate-y-0');
                        $statusMenu.addClass('opacity-0 translate-y-2');
                        setTimeout(() => $statusMenu.addClass('hidden'), 200);
                        $statusBtn.find('svg').removeClass('rotate-180');
                    }
                });

                $statusOptions.on('click', function() {
                    const $option = $(this);
                    const value = $option.data('value');
                    $statusInput.val(value);
                    $selectedLabel.text($option.text().trim());

                    $statusMenu.removeClass('opacity-100 translate-y-0');
                    $statusMenu.addClass('opacity-0 translate-y-2');
                    setTimeout(() => $statusMenu.addClass('hidden'), 200);
                    $statusBtn.find('svg').removeClass('rotate-180');

                    loadTable();
                });

                // Close dropdowns when clicking outside
                $(document).on('click', function() {
                    $('.dropdown-menu').each(function() {
                        const $menu = $(this);
                        if (!$menu.hasClass('hidden')) {
                            $menu.removeClass('opacity-100 translate-y-0');
                            $menu.addClass('opacity-0 translate-y-2');
                            setTimeout(() => $menu.addClass('hidden'), 200);
                            const $btn = $menu.closest('.custom-dropdown').find('.dropdown-btn svg');
                            if ($btn.length) $btn.removeClass('rotate-180');
                        }
                    });
                });

                // Debounce function for search typing
                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }

                // Handle typing in search (debounced)
                $searchInput.on('input', debounce(function() {
                    loadTable();
                }, 500));

                // Prevent form submit on enter
                $filterForm.on('submit', function(e) {
                    e.preventDefault();
                    loadTable();
                });

                // Prevent dropdown button from submitting form
                $statusBtn.on('click', function(e) {
                    e.preventDefault();
                });


                // === AJAX Table Loading ===
                function loadTable(url = null) {
                    const $wrapper = $('#kyc-wrapper');
                    const $spinner = $('#loading-spinner');
                    const formParams = $filterForm.serialize();
                    const fetchUrl = url || `${$filterForm.attr('action')}?${formParams}`;

                    // Update URL without reloading
                    window.history.pushState({}, '', fetchUrl);

                    $spinner.removeClass('hidden');

                    $.ajax({
                        url: fetchUrl,
                        type: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(html) {
                            const newTableContent = $(html).find('#kyc-wrapper');

                            if (newTableContent.length) {
                                // Only replace the actual table and pagination, not the entire wrapper to preserve filters
                                const $newTable = newTableContent.find('table');
                                const $newPagination = newTableContent.find('.ajax-pagination');

                                const $currentTable = $wrapper.find('table');
                                const $currentPagination = $wrapper.find('.ajax-pagination');

                                if ($currentTable.length && $newTable.length) $currentTable.html($newTable
                                    .html());

                                if ($currentPagination.length && $newPagination.length) {
                                    $currentPagination.html($newPagination.html());
                                } else if ($newPagination.length && !$currentPagination.length) {
                                    $wrapper.append($newPagination);
                                } else if (!$newPagination.length && $currentPagination.length) {
                                    $currentPagination.remove();
                                }
                            }

                            $spinner.addClass('hidden');
                        },
                        error: function(xhr) {
                            console.error('Error loading table:', xhr);
                            $spinner.addClass('hidden');
                            toastNotification("Failed to load data", "error");
                        }
                    });
                }

                // AJAX Pagination
                $(document).on('click', '.ajax-pagination a', function(e) {
                    e.preventDefault();
                    if (!$(this).hasClass('disabled')) {
                        var url = $(this).attr('href');
                        loadTable(url);
                    }
                });


                // === Status Distribution Chart ===
                const initStatusChart = () => {
                    const statusCtx = document.getElementById('statusDistributionChart');
                    if (statusCtx) {
                        const distData = @json($statusDistribution);
                        if (window.statusDistributionChartInstance) {
                            window.statusDistributionChartInstance.destroy();
                        }

                        // Use plugin to draw background color (if needed, but Doughnut doesn't usually need it)
                        window.statusDistributionChartInstance = new Chart(statusCtx, {
                            type: 'doughnut',
                            data: {
                                labels: distData.labels,
                                datasets: [{
                                    data: distData.data,
                                    backgroundColor: distData.colors,
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '75%',
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            color: '#94a3b8',
                                            usePointStyle: true,
                                            padding: 20,
                                            font: {
                                                family: "'Inter', sans-serif",
                                                size: 11
                                            }
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: '#1e293b',
                                        titleColor: '#fff',
                                        bodyColor: '#cbd5e1',
                                        borderColor: 'rgba(255,255,255,0.1)',
                                        borderWidth: 1,
                                        padding: 10,
                                        displayColors: true,
                                        boxPadding: 4,
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.label || '';
                                                if (label) {
                                                    label += ': ';
                                                }
                                                if (context.parsed !== null) {
                                                    label += context.parsed + ' Submissions';
                                                }
                                                return label;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                };

                initStatusChart();


                // === Modals ===
                let currentDeleteId = null;

                window.confirmDelete = function(id) {
                    currentDeleteId = id;
                    const $modal = $('#deleteModal');
                    $modal.removeClass('hidden');

                    // Reset styling
                    const $dialog = $modal.find('.transform');
                    $dialog.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                };

                window.closeDeleteModal = function() {
                    const $modal = $('#deleteModal');
                    const $dialog = $modal.find('.transform');

                    $dialog.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');

                    setTimeout(() => {
                        $modal.addClass('hidden');
                        currentDeleteId = null;
                    }, 200);
                };

                // Delete Action
                $('#confirmDeleteBtn').on('click', function() {
                    if (!currentDeleteId) return;

                    const $btn = $(this);
                    const $text = $btn.find('.btn-text');
                    const $loader = $btn.find('.btn-loader');

                    $btn.prop('disabled', true);
                    $text.addClass('invisible');
                    $loader.removeClass('hidden');

                    // Correct URL generation for deletion
                    let submitUrl = "{{ route('admin.kyc.delete', ':id') }}";
                    submitUrl = submitUrl.replace(':id', currentDeleteId);

                    $.ajax({
                        url: submitUrl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success || response.status === 'success') {
                                closeDeleteModal();
                                toastNotification(response.message || "KYC record deleted.",
                                    "success");
                                loadTable(window.location.href);
                            } else {
                                toastNotification(response.message || "Failed to delete KYC record",
                                    "error");
                            }
                        },
                        error: function(xhr) {
                            toastNotification("An error occurred.", "error");
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                            $text.removeClass('invisible');
                            $loader.addClass('hidden');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
