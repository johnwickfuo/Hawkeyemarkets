@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="kyc-view-content" class="space-y-8">
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

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.kyc.index') }}"
                        class="p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-text-secondary hover:text-white transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18">
                            </path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-white tracking-tight">{{ __('KYC Submission Details') }}</h1>
                        <p class="text-text-secondary text-sm font-mono mt-1 uppercase tracking-wider">
                            {{ __('Submitted on') }}
                            {{ $kyc->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @php
                        $statusClasses = [
                            'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                            'approved' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                            'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        ];
                        $class = $statusClasses[$kyc->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                    @endphp
                    <span
                        class="px-4 py-2 rounded-xl border {{ $class }} font-black uppercase tracking-widest text-[11px] flex items-center gap-2">
                        @if ($kyc->status === 'pending')
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        @elseif ($kyc->status === 'approved')
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        @elseif ($kyc->status === 'rejected')
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                        @endif
                        {{ strtoupper(__($kyc->status)) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main User Details --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Client Information --}}
                    <div
                        class="bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-white/10 transition-colors">
                        <div
                            class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                        </div>
                        <div class="relative z-10 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                @if ($kyc->user->photo)
                                    <div
                                        class="w-16 h-16 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                        <img src="{{ asset('storage/profile/' . $kyc->user->photo) }}"
                                            alt="{{ $kyc->user->username }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div
                                        class="w-16 h-16 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-2xl shrink-0">
                                        {{ substr($kyc->user->username, 0, 2) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">
                                        {{ __('Submitted By') }}</p>
                                    <a href="{{ route('admin.users.detail', $kyc->user_id) }}"
                                        class="text-xl font-bold text-white hover:text-accent-primary transition-colors cursor-pointer">{{ $kyc->user->first_name }}
                                        {{ $kyc->user->last_name }}</a>
                                    <p class="text-sm text-slate-400 font-mono mt-0.5">{{ $kyc->user->email }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.detail', $kyc->user_id) }}"
                                class="p-3 bg-white/5 hover:bg-white/10 text-white rounded-xl transition-colors cursor-pointer group-hover:scale-105"
                                title="{{ __('View Profile') }}">
                                <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Personal Information Grid --}}
                    <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative">
                        <div
                            class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-2xl">
                        </div>
                        <h3 class="text-lg font-bold text-white mb-6 tracking-tight relative z-10">
                            {{ __('Personal Information') }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                            <div class="space-y-1 bg-white/[0.02] p-4 rounded-xl border border-white/5">
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                    {{ __('Date of Birth') }}</p>
                                <p class="text-white font-mono font-medium">
                                    {{ $kyc->date_of_birth ? $kyc->date_of_birth->format('M d, Y') : __('Not Provided') }}
                                </p>
                            </div>

                            <div class="space-y-1 bg-white/[0.02] p-4 rounded-xl border border-white/5">
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                    {{ __('Phone Number') }}</p>
                                <p class="text-white font-mono font-medium">{{ $kyc->phone_code }} {{ $kyc->phone }}</p>
                            </div>

                            <div class="space-y-1 bg-white/[0.02] p-4 rounded-xl border border-white/5 md:col-span-2">
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                    {{ __('Residential Address') }}</p>
                                <p class="text-white font-medium">{{ $kyc->address_line_1 }}</p>
                                <p class="text-white text-sm opacity-80 mt-1">{{ $kyc->city }}, {{ $kyc->zip }},
                                    {{ $kyc->country }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Uploaded Documents --}}
                    <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative">
                        <div
                            class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-2xl">
                        </div>
                        <div class="flex items-center justify-between mb-6 relative z-10 border-b border-white/5 pb-4">
                            <h3 class="text-lg font-bold text-white tracking-tight">{{ __('Identity Documents') }}</h3>
                            <span
                                class="px-3 py-1 bg-white/5 rounded-lg text-xs font-bold uppercase tracking-widest text-text-secondary border border-white/10">
                                {{ __(str_replace('_', ' ', $kyc->document_type)) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10 mt-6">
                            @if ($kyc->document_front)
                                <div
                                    class="group relative bg-black/40 rounded-xl overflow-hidden border border-white/10 aspect-video col-span-1 sm:col-span-2 md:col-span-1 block">
                                    <img src="{{ asset('storage/' . $kyc->document_front) }}" alt="Document Front"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                                        <a href="{{ asset('storage/' . $kyc->document_front) }}" target="_blank"
                                            class="px-4 py-2 bg-accent-primary text-white rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-accent-primary/80 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            {{ __('View Full') }}
                                        </a>
                                    </div>
                                    <div
                                        class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-widest rounded-md border border-white/10">
                                        {{ __('Front Side') }}
                                    </div>
                                </div>
                            @endif

                            @if ($kyc->document_back)
                                <div
                                    class="group relative bg-black/40 rounded-xl overflow-hidden border border-white/10 aspect-video col-span-1 sm:col-span-2 md:col-span-1 block">
                                    <img src="{{ asset('storage/' . $kyc->document_back) }}" alt="Document Back"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                                        <a href="{{ asset('storage/' . $kyc->document_back) }}" target="_blank"
                                            class="px-4 py-2 bg-accent-primary text-white rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-accent-primary/80 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            {{ __('View Full') }}
                                        </a>
                                    </div>
                                    <div
                                        class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-widest rounded-md border border-white/10">
                                        {{ __('Back Side') }}
                                    </div>
                                </div>
                            @else
                                <!-- Spacer if no back document is needed -->
                                <div class="hidden md:block"></div>
                            @endif

                            @if ($kyc->selfie)
                                <div
                                    class="group relative bg-black/40 rounded-xl overflow-hidden border border-white/10 aspect-[3/4] col-span-1 block max-h-[400px]">
                                    <img src="{{ asset('storage/' . $kyc->selfie) }}" alt="User Selfie"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div
                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                                        <a href="{{ asset('storage/' . $kyc->selfie) }}" target="_blank"
                                            class="px-4 py-2 bg-accent-primary text-white rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-accent-primary/80 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            {{ __('View Full') }}
                                        </a>
                                    </div>
                                    <div
                                        class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-widest rounded-md border border-white/10">
                                        {{ __('Selfie') }}
                                    </div>
                                </div>
                            @endif

                            @if ($kyc->proof_address)
                                <div
                                    class="group relative bg-black/40 rounded-xl overflow-hidden border border-white/10 aspect-[3/4] col-span-1 block max-h-[400px]">
                                    <img src="{{ asset('storage/' . $kyc->proof_address) }}" alt="Proof of Address"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 object-top">
                                    <div
                                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm">
                                        <a href="{{ asset('storage/' . $kyc->proof_address) }}" target="_blank"
                                            class="px-4 py-2 bg-accent-primary text-white rounded-lg text-sm font-bold flex items-center gap-2 hover:bg-accent-primary/80 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            {{ __('View Full') }}
                                        </a>
                                    </div>
                                    <div
                                        class="absolute top-3 left-3 px-2.5 py-1 bg-black/80 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-widest rounded-md border border-white/10">
                                        {{ __('Proof of Address') }}
                                    </div>
                                </div>
                            @else
                                <!-- Spacer -->
                                <div class="hidden sm:block"></div>
                            @endif

                        </div>
                    </div>

                </div>

                {{-- Sidebar (Actions) --}}
                <div class="space-y-6">

                    {{-- Action Card --}}
                    <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative">
                        <div
                            class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-2xl">
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-bold text-white mb-2">{{ __('Review Decision') }}</h3>
                            <p class="text-xs text-text-secondary mb-6">
                                {{ __('Please review the provided documents carefully before making a decision. Approving will update the user account limit restrictions.') }}
                            </p>

                            @if ($kyc->status === 'pending')
                                <div class="space-y-3">
                                    <button type="button" onclick="openApproveModal()"
                                        class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 group cursor-pointer focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2 focus:ring-offset-secondary">
                                        <svg class="w-5 h-5 text-emerald-100 group-hover:scale-110 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('Approve KYC') }}
                                    </button>

                                    <button type="button" onclick="openRejectModal()"
                                        class="w-full py-4 bg-white/5 hover:bg-red-500/10 text-white hover:text-red-400 border border-white/10 hover:border-red-500/30 font-bold rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 focus:ring-offset-secondary">
                                        <svg class="w-5 h-5 text-text-secondary group-hover:text-red-400 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        {{ __('Reject KYC') }}
                                    </button>
                                </div>
                            @else
                                <div
                                    class="p-4 rounded-xl border {{ $kyc->status === 'approved' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border-red-500/20 text-red-400' }} flex flex-col items-center justify-center gap-2 text-center">
                                    @if ($kyc->status === 'approved')
                                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="font-bold">{{ __('This KYC has been approved') }}</p>
                                    @else
                                        <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        <p class="font-bold">{{ __('This KYC has been rejected') }}</p>
                                    @endif
                                    <p class="text-xs opacity-70 mt-1">
                                        {{ __('Processed on :date', ['date' => $kyc->updated_at->format('M d, Y')]) }}</p>
                                </div>

                                @if ($kyc->status === 'rejected' && $kyc->rejection_reason)
                                    <div class="mt-4 p-4 bg-black/40 border border-white/5 rounded-xl">
                                        <p
                                            class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-2">
                                            {{ __('Rejection Reason') }}</p>
                                        <p class="text-white text-sm italic">"{{ $kyc->rejection_reason }}"</p>
                                    </div>
                                @endif

                                <div class="mt-6 pt-6 border-t border-white/5">
                                    <button type="button" onclick="openApproveModal()"
                                        class="w-full text-center text-sm font-bold text-text-secondary hover:text-white transition-colors">
                                        {{ __('Change Status to Approved') }}
                                    </button>
                                    <button type="button" onclick="openRejectModal()"
                                        class="w-full text-center text-sm font-bold text-text-secondary hover:text-white transition-colors mt-3">
                                        {{ __('Change Status to Rejected') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Status Timeline --}}
                    <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white mb-6 uppercase tracking-wider">
                            {{ __('Submission History') }}
                        </h3>
                        <div class="relative border-l border-white/10 ml-3 space-y-6">

                            <div class="relative pl-6">
                                <span
                                    class="absolute -left-1.5 top-1.5 w-3 h-3 bg-white/20 border-2 border-secondary rounded-full"></span>
                                <div class="text-sm font-medium text-white mb-0.5">{{ __('Submitted') }}</div>
                                <div class="text-[11px] text-text-secondary font-mono">
                                    {{ $kyc->created_at->format('M d, Y h:i A') }}</div>
                            </div>

                            @if ($kyc->status !== 'pending')
                                <div class="relative pl-6">
                                    @if ($kyc->status === 'approved')
                                        <span
                                            class="absolute -left-1.5 top-1.5 w-3 h-3 bg-emerald-500 border-2 border-secondary rounded-full"></span>
                                        <div class="text-sm font-medium text-emerald-400 mb-0.5">{{ __('Approved') }}
                                        </div>
                                    @else
                                        <span
                                            class="absolute -left-1.5 top-1.5 w-3 h-3 bg-red-500 border-2 border-secondary rounded-full"></span>
                                        <div class="text-sm font-medium text-red-400 mb-0.5">{{ __('Rejected') }}</div>
                                    @endif
                                    <div class="text-[11px] text-text-secondary font-mono">
                                        {{ $kyc->updated_at->format('M d, Y h:i A') }}</div>
                                </div>
                            @else
                                <div class="relative pl-6">
                                    <span
                                        class="absolute -left-1.5 top-1.5 w-3 h-3 bg-amber-500 border-2 border-secondary rounded-full animate-pulse"></span>
                                    <div class="text-sm font-medium text-amber-400 mb-0.5">{{ __('Awaiting Review') }}
                                    </div>
                                    <div class="text-[11px] text-text-secondary font-mono">{{ __('Current state') }}</div>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>

        @endif
    </div>


    {{-- MODALS --}}

    {{-- Approve Modal --}}
    <div id="approveModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-primary-dark/80 backdrop-blur-sm z-40 modal-overlay cursor-pointer"
            onclick="closeApproveModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                class="relative bg-secondary-dark border border-white/10 rounded-2xl shadow-2xl text-left overflow-hidden transform transition-all sm:max-w-md w-full z-50 animate-modal-enter">

                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeApproveModal()"
                        class="text-text-secondary hover:text-white focus:outline-none transition-colors cursor-pointer">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="approveForm" action="{{ route('admin.kyc.update', $kyc->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="approved">

                    <div class="px-6 py-8 sm:flex sm:items-start text-center sm:text-left">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-4 flex-1">
                            <h3 class="text-lg font-bold text-white mb-2" id="modal-title">
                                {{ __('Approve KYC') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-text-secondary">
                                    {{ __('Are you sure you want to approve this KYC submission? The user will be notified and any account limitations will be lifted.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-black/20 px-6 py-4 border-t border-white/5 flex flex-col sm:flex-row-reverse sm:gap-3">
                        <button type="submit" id="confirmApproveBtn"
                            class="w-full inline-flex justify-center items-center rounded-xl bg-emerald-600 hover:bg-emerald-500 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition-all focus:outline-none sm:w-auto relative overflow-hidden group">
                            <span class="btn-text">{{ __('Approve') }}</span>
                            <div
                                class="btn-loader hidden absolute inset-0 flex items-center justify-center bg-emerald-600">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </button>
                        <button type="button" onclick="closeApproveModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-xl bg-white/5 border border-white/10 px-4 py-2 text-sm font-medium text-white hover:bg-white/10 transition-all focus:outline-none sm:mt-0 sm:w-auto">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 bg-primary-dark/80 backdrop-blur-sm z-40 modal-overlay cursor-pointer"
            onclick="closeRejectModal()"></div>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div
                class="relative bg-secondary-dark border border-white/10 rounded-2xl shadow-2xl text-left overflow-hidden transform transition-all sm:max-w-md w-full z-50 animate-modal-enter">

                <div class="absolute top-0 right-0 pt-4 pr-4">
                    <button type="button" onclick="closeRejectModal()"
                        class="text-text-secondary hover:text-white focus:outline-none transition-colors cursor-pointer">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="rejectForm" action="{{ route('admin.kyc.update', $kyc->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">

                    <div class="px-6 py-8 sm:flex sm:items-start text-center sm:text-left pb-4">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-4 flex-1">
                            <h3 class="text-lg font-bold text-white mb-2" id="modal-title">
                                {{ __('Reject KYC') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-text-secondary">
                                    {{ __('Are you sure you want to reject this KYC submission? Please provide a reason so the user knows what to correct.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-0">
                        <label for="rejection_reason"
                            class="block text-sm font-bold text-white mb-2">{{ __('Rejection Reason') }} <span
                                class="text-red-500">*</span></label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                            class="w-full bg-[#0F172A] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none transition-all resize-none"
                            placeholder="{{ __('E.g. The ID image provided is blurry and unreadable.') }}"></textarea>
                    </div>

                    <div class="bg-black/20 px-6 py-4 border-t border-white/5 flex flex-col sm:flex-row-reverse sm:gap-3">
                        <button type="submit" id="confirmRejectBtn"
                            class="w-full inline-flex justify-center items-center rounded-xl bg-red-600 hover:bg-red-500 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition-all focus:outline-none sm:w-auto relative overflow-hidden group">
                            <span class="btn-text">{{ __('Reject') }}</span>
                            <div class="btn-loader hidden absolute inset-0 flex items-center justify-center bg-red-600">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </button>
                        <button type="button" onclick="closeRejectModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-xl bg-white/5 border border-white/10 px-4 py-2 text-sm font-medium text-white hover:bg-white/10 transition-all focus:outline-none sm:mt-0 sm:w-auto">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Javascript --}}
    @push('scripts')
        <script>
            $(document).ready(function() {
                // Approve Modal
                window.openApproveModal = function() {
                    const $modal = $('#approveModal');
                    $modal.removeClass('hidden');
                    const $dialog = $modal.find('.transform');
                    $dialog.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
                };

                window.closeApproveModal = function() {
                    const $modal = $('#approveModal');
                    const $dialog = $modal.find('.transform');
                    $dialog.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                    setTimeout(() => {
                        $modal.addClass('hidden');
                    }, 200);
                };

                // Reject Modal
                window.openRejectModal = function() {
                    const $modal = $('#rejectModal');
                    $modal.removeClass('hidden');
                    const $dialog = $modal.find('.transform');
                    $dialog.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');

                    // Focus the textarea
                    setTimeout(() => {
                        $('#rejection_reason').focus();
                    }, 100);
                };

                window.closeRejectModal = function() {
                    const $modal = $('#rejectModal');
                    const $dialog = $modal.find('.transform');
                    $dialog.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                    setTimeout(() => {
                        $modal.addClass('hidden');
                    }, 200);
                };

                // Form Submissions
                function handleFormSubmit(formId, btnId) {
                    const $form = $('#' + formId);

                    if (!$form.length) return;

                    $form.on('submit', function(e) {
                        e.preventDefault();

                        if (formId === 'rejectForm') {
                            const reason = $('#rejection_reason').val().trim();
                            if (!reason) {
                                toastNotification("Rejection reason is required.", "error");
                                return;
                            }
                        }

                        const $btn = $('#' + btnId);
                        const $text = $btn.find('.btn-text');
                        const $loader = $btn.find('.btn-loader');

                        $btn.prop('disabled', true);
                        $text.addClass('invisible');
                        $loader.removeClass('hidden');

                        const formData = new FormData(this);

                        $.ajax({
                            url: $form.attr('action'),
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-Token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success || response.status === 'success') {
                                    toastNotification(response.message ||
                                        "KYC record updated successfully.", "success");
                                    closeApproveModal();
                                    closeRejectModal();
                                    $btn.prop('disabled', false);
                                    $text.removeClass('invisible');
                                    $loader.addClass('hidden');

                                    // ajax page reload and update
                                    $.ajax({
                                        url: window.location.href,
                                        type: 'GET',
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                        success: function(html) {
                                            const newContent = $(html).find(
                                                '#kyc-view-content');
                                            if (newContent.length) {
                                                $('#kyc-view-content').html(newContent
                                                    .html());
                                            } else {
                                                window.location.reload();
                                            }
                                        },
                                        error: function() {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    toastNotification(response.message ||
                                        "Failed to update KYC record.", "error");
                                    $btn.prop('disabled', false);
                                    $text.removeClass('invisible');
                                    $loader.addClass('hidden');
                                }
                            },
                            error: function(xhr) {
                                console.error('Error:', xhr);
                                let errorMessage = "An error occurred.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                toastNotification(errorMessage, "error");
                                $btn.prop('disabled', false);
                                $text.removeClass('invisible');
                                $loader.addClass('hidden');
                            }
                        });
                    });
                }

                handleFormSubmit('approveForm', 'confirmApproveBtn');
                handleFormSubmit('rejectForm', 'confirmRejectBtn');
            });
        </script>
    @endpush
@endsection
