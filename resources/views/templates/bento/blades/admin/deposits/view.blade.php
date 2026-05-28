@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="deposit-view-content" class="space-y-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.deposits.index') }}"
                    class="p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-text-secondary hover:text-white transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">{{ __('Deposit Details') }}</h1>
                    <p class="text-text-secondary text-sm font-mono mt-1 uppercase tracking-wider">{{ __('Transaction') }}
                        #{{ $deposit->transaction_reference }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                        'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                        'failed' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        'partial_payment' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                    ];
                    $class = $statusClasses[$deposit->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                    $statusLabel =
                        $deposit->status === 'partial_payment' ? __('Partial Payment') : __($deposit->status);
                @endphp
                <span
                    class="px-4 py-2 rounded-xl border {{ $class }} font-black uppercase tracking-widest text-[11px]">
                    {{ $statusLabel }}
                </span>

                <button type="button"
                    class="btn-edit-status px-6 py-2 bg-accent-primary hover:bg-accent-primary/90 text-white font-bold rounded-xl transition-all shadow-lg shadow-accent-primary/20 cursor-pointer"
                    data-id="{{ $deposit->id }}" data-status="{{ $deposit->status }}">
                    {{ __('Update Status') }}
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Client Information --}}
                <div
                    class="bg-secondary border border-white/5 rounded-2xl p-6 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none">
                    </div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            @if ($deposit->user->photo)
                                <div
                                    class="w-12 h-12 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/profile/' . $deposit->user->photo) }}"
                                        alt="{{ $deposit->user->username }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs shrink-0">
                                    {{ substr($deposit->user->username, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">
                                    {{ __('Deposited By') }}</p>
                                <a href="{{ route('admin.users.detail', $deposit->user_id) }}"
                                    class="text-base font-bold text-white hover:text-accent-primary transition-colors cursor-pointer">{{ $deposit->user->fullname }}</a>
                                <p class="text-sm text-slate-400 font-mono mt-0.5">{{ $deposit->user->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.detail', $deposit->user_id) }}"
                            class="p-3 bg-white/5 hover:bg-white/10 text-white rounded-xl transition-colors cursor-pointer group-hover:scale-105"
                            title="{{ __('View Profile') }}">
                            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Financial Summary --}}
                <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-2xl">
                    </div>
                    <h3 class="text-lg font-bold text-white mb-6 tracking-tight relative z-10">{{ __('Payment Summary') }}
                    </h3>

                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-text-secondary text-sm font-medium">{{ __('Amount Requested') }}</span>
                            <span class="text-white font-mono font-bold">{{ showAmount($deposit->amount) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-text-secondary text-sm font-medium">{{ __('Fees') }}
                                <span
                                    class="text-xs text-slate-500 ml-1">({{ number_format($deposit->fee_percent, 2) }}%)</span></span>
                            <span class="text-red-400 font-mono font-bold">+ {{ showAmount($deposit->fee_amount) }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center py-4 border-b border-white/5 bg-white/[0.02] -mx-6 px-6 shadow-inner">
                            <span class="text-white font-bold">{{ __('Total Payable') }}</span>
                            <span
                                class="text-2xl font-black text-emerald-400 font-mono">{{ showAmount($deposit->total_amount) }}</span>
                        </div>

                        @if ($deposit->exchange_rate != 1)
                            <div class="bg-[#0F172A] rounded-xl p-5 border border-white/10 mt-4 shadow-lg">
                                <div class="flex justify-between items-center text-sm mb-3">
                                    <span
                                        class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">{{ __('Exchange Rate') }}</span>
                                    <span class="text-white font-mono">1 {{ getSetting('currency') }} =
                                        {{ number_format($deposit->exchange_rate, 8) }} {{ $deposit->currency }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-white/5">
                                    <span class="text-white font-bold">{{ __('Converted Amount') }}</span>
                                    <span
                                        class="text-xl font-black text-accent-primary font-mono drop-shadow-[0_0_8px_rgba(var(--color-accent-primary),0.5)]">
                                        {{ number_format($deposit->converted_amount, 8) }} {{ $deposit->currency }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Method Details --}}
                <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-2xl">
                    </div>
                    <h3 class="text-lg font-bold text-white mb-6 tracking-tight relative z-10">{{ __('Payment Gateway') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 relative z-10">
                        <div class="space-y-2 bg-white/5 p-4 rounded-xl border border-white/5">
                            <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                {{ __('Method') }}</p>
                            <p class="text-white font-bold text-base">{{ $deposit->paymentMethod->name ?? __('Unknown') }}
                            </p>
                        </div>

                        @if ($deposit->transaction_hash)
                            <div class="space-y-2 md:col-span-2 bg-white/5 p-4 rounded-xl border border-white/5">
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                    {{ __('Transaction ID / Hash') }}</p>
                                <div class="flex items-center gap-3">
                                    <p class="text-accent-primary font-mono text-sm break-all">
                                        {{ $deposit->transaction_hash }}
                                    </p>
                                    <button
                                        class="p-2 bg-black/40 hover:bg-black/80 rounded-lg text-text-secondary hover:text-white transition-colors copy-btn cursor-pointer shrink-0"
                                        data-clipboard-text="{{ $deposit->transaction_hash }}"
                                        title="{{ __('Copy Hash') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Structured Data Loop --}}
                    @php
                        $raw_structure = $deposit->getAttributes()['structured_data'] ?? null;
                        $details = null;
                        if (is_array($raw_structure)) {
                            $details = $raw_structure;
                        } elseif (is_string($raw_structure)) {
                            $details = json_decode($raw_structure, true);
                        }
                    @endphp

                    @if ($details && is_array($details))
                        <div class="pt-6 border-t border-white/5 relative z-10">
                            <h4 class="text-sm font-bold text-white mb-4">{{ __('User Submitted Data') }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($details as $key => $value)
                                    @continue($key === 'transaction_hash') {{-- Skip hash --}}
                                    <div class="space-y-1.5 p-4 bg-black/20 rounded-xl border border-white/5">
                                        <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}</p>
                                        <p class="text-white font-mono text-sm break-all">
                                            {{ is_array($value) ? json_encode($value) : $value }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Payment Proof --}}
                @if ($deposit->payment_proof)
                    <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative">
                        <div
                            class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-2xl">
                        </div>
                        <h3 class="text-lg font-bold text-white mb-4 tracking-tight relative z-10">
                            {{ __('Payment Proof') }}</h3>
                        <div
                            class="rounded-xl overflow-hidden border border-white/10 bg-black/40 group relative h-56 cursor-pointer relative z-10 shadow-inner">
                            <img src="{{ asset('storage/' . $deposit->payment_proof) }}" alt="Proof"
                                class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 backdrop-blur-sm">
                                <a href="{{ asset('storage/' . $deposit->payment_proof) }}" target="_blank"
                                    class="px-5 py-2.5 bg-white/10 hover:bg-white text-white hover:text-black font-bold tracking-wide rounded-xl transform scale-90 group-hover:scale-100 transition-all border border-white/20 hover:border-transparent flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4">
                                        </path>
                                    </svg>
                                    {{ __('View Full Image') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Timeline --}}
                <div class="bg-secondary border border-white/5 rounded-2xl p-6 relative">
                    <div
                        class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-10 pointer-events-none rounded-2xl">
                    </div>
                    <h3 class="text-lg font-bold text-white mb-6 tracking-tight relative z-10">{{ __('Timeline') }}</h3>
                    <div class="relative pl-5 border-l-2 border-white/10 ml-3 space-y-8 relative z-10">
                        <div class="relative">
                            <div
                                class="absolute -left-[27px] top-1 w-4 h-4 rounded-full bg-slate-500 ring-4 ring-secondary shadow-[0_0_10px_rgba(148,163,184,0.5)]">
                            </div>
                            <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                {{ __('Initiated') }}</p>
                            <p class="text-white font-bold text-sm mt-1.5">{{ $deposit->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-slate-500 font-mono text-xs">{{ $deposit->created_at->format('H:i:s A') }}</p>
                        </div>

                        @if ($deposit->status === 'pending' && $deposit->expires_at)
                            <div class="relative">
                                <div
                                    class="absolute -left-[27px] top-1 w-4 h-4 rounded-full bg-yellow-500 ring-4 ring-secondary shadow-[0_0_10px_rgba(234,179,8,0.5)] animate-pulse">
                                </div>
                                <p class="text-[10px] text-yellow-500 uppercase tracking-widest font-bold">
                                    {{ __('Expires At') }}</p>
                                <p class="text-white font-bold text-sm mt-1.5">
                                    {{ \Carbon\Carbon::createFromTimestamp($deposit->expires_at)->format('M d, Y') }}</p>
                                <p class="text-slate-500 font-mono text-xs">
                                    {{ \Carbon\Carbon::createFromTimestamp($deposit->expires_at)->format('H:i:s A') }}</p>
                            </div>
                        @else
                            @php
                                $dotColor =
                                    $deposit->status === 'completed'
                                        ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]'
                                        : ($deposit->status === 'failed'
                                            ? 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]'
                                            : ($deposit->status === 'partial_payment'
                                                ? 'bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]'
                                                : 'bg-slate-500 shadow-[0_0_10px_rgba(148,163,184,0.5)]'));
                            @endphp
                            <div class="relative">
                                <div
                                    class="absolute -left-[27px] top-1 w-4 h-4 rounded-full {{ $dotColor }} ring-4 ring-secondary">
                                </div>
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                    {{ __('Last Check') }}</p>
                                <p class="text-white font-bold text-sm mt-1.5">
                                    {{ $deposit->updated_at->format('M d, Y') }}</p>
                                <p class="text-slate-500 font-mono text-xs">{{ $deposit->updated_at->format('H:i:s A') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="relative group cursor-pointer btn-delete-deposit" data-id="{{ $deposit->id }}">
                    <div
                        class="absolute inset-0 bg-red-500/10 rounded-2xl blur-xl group-hover:bg-red-500/20 transition-all opacity-0 group-hover:opacity-100">
                    </div>
                    <div
                        class="bg-[#1E293B] hover:bg-red-500/10 border border-white/5 hover:border-red-500/30 rounded-2xl p-4 flex items-center justify-between transition-all relative z-10">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-2 bg-red-500/10 text-red-400 rounded-lg group-hover:bg-red-500 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-sm group-hover:text-red-400 transition-colors">
                                    {{ __('Delete Deposit') }}</h4>
                                <p class="text-[10px] text-text-secondary mt-0.5">
                                    {{ __('Permanently remove this record') }}</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-text-secondary group-hover:text-red-400 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Edit Status Modal --}}
    <div id="edit-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
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
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Edit Status') }}</h3>
                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-widest font-mono">
                                {{ __('Update deposit status') }}</p>
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
                        <input type="hidden" name="deposit_id" id="edit-deposit-id">

                        <div class="pb-20">
                            <label
                                class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 font-mono ml-1">{{ __('Select New Status') }}</label>
                            <div class="relative custom-dropdown" id="edit-status-dropdown" style="z-index: 50;">
                                <button type="button"
                                    class="dropdown-btn w-full flex items-center justify-between gap-3 px-4 py-3.5 rounded-xl bg-white/[0.03] border border-white/10 text-white text-sm hover:bg-white/5 focus:border-accent-primary/50 transition-all cursor-pointer">
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
                                    @foreach (['pending', 'partial_payment', 'completed', 'failed'] as $st)
                                        <div class="dropdown-option px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors cursor-pointer capitalize font-medium"
                                            data-value="{{ $st }}">
                                            @if ($st == 'partial_payment')
                                                {{ __('Partial Payment') }}
                                            @else
                                                {{ ucfirst($st) }}
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex gap-3">
                            <button type="button"
                                class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">{{ __('Cancel') }}</button>
                            <button type="submit"
                                class="flex-1 px-4 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-primary/90 shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">{{ __('Save Changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity modal-close cursor-pointer">
            </div>

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
                        {{ __('Are you sure you want to delete this deposit? This action cannot be undone.') }}</p>

                    <div class="flex gap-3">
                        <input type="hidden" id="delete-deposit-id">
                        <button type="button"
                            class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">{{ __('Cancel') }}</button>
                        <button type="button" id="confirm-delete"
                            class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-bold hover:bg-red-600 shadow-lg shadow-red-500/20 transition-all cursor-pointer">{{ __('Delete Forever') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Dropdowns functionality
            $(document).on('click', '.dropdown-btn', function(e) {
                e.stopPropagation();
                $('.dropdown-menu').not($(this).siblings('.dropdown-menu')).addClass('hidden');
                $('.dropdown-icon').not($(this).find('.dropdown-icon')).removeClass('rotate-180');

                const menu = $(this).siblings('.dropdown-menu');
                const icon = $(this).find('.dropdown-icon');

                menu.toggleClass('hidden');
                icon.toggleClass('rotate-180');
            });

            $(document).on('click', function() {
                $('.dropdown-menu').addClass('hidden');
                $('.dropdown-icon').removeClass('rotate-180');
            });

            // Clipboard Logic
            $(document).on('click', '.copy-btn', function() {
                const text = $(this).data('clipboard-text');
                navigator.clipboard.writeText(text).then(() => {
                    toastNotification('{{ __('Transaction Hash copied to clipboard') }}',
                        'success');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                });
            });

            // Modals Logic
            function openModal(id) {
                const $modal = $(`#${id}`);
                $modal.removeClass('hidden');
                setTimeout(() => {
                    $modal.find('.transform').removeClass('scale-95 opacity-0').addClass(
                        'scale-100 opacity-100');
                    $modal.find('.fixed.inset-0').removeClass('opacity-0').addClass('opacity-100');
                }, 10);
            }

            function closeModal(id) {
                const $modal = $(`#${id}`);
                $modal.find('.transform').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
                $modal.find('.fixed.inset-0').removeClass('opacity-100').addClass('opacity-0');
                setTimeout(() => {
                    $modal.addClass('hidden');
                }, 300);
            }

            $('.modal-close').on('click', function() {
                const modalId = $(this).closest('.fixed.inset-0.z-\\[100\\]').attr('id');
                closeModal(modalId);
            });

            // Status Update logic
            $(document).on('click', '.btn-edit-status', function() {
                const id = $(this).data('id');
                const status = $(this).data('status');

                $('#edit-deposit-id').val(id);
                $('#edit-status-value').val(status);

                // Set initial label
                let statusLabel = status;
                if (status === 'partial_payment') {
                    statusLabel = '{{ __('Partial Payment') }}';
                } else {
                    statusLabel = status.charAt(0).toUpperCase() + status.slice(1);
                }
                $('#edit-status-dropdown .selected-label').text(statusLabel);

                openModal('edit-modal');
            });

            $('#edit-status-dropdown .dropdown-option').on('click', function() {
                const val = $(this).data('value');
                const label = $(this).text().trim();

                $('#edit-status-value').val(val);
                $('#edit-status-dropdown .selected-label').text(label);
            });

            $('#edit-status-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#edit-deposit-id').val();
                const status = $('#edit-status-value').val();
                const $submitBtn = $(this).find('button[type="submit"]');
                const originalText = $submitBtn.text();

                $submitBtn.text('{{ __('Saving...') }}').prop('disabled', true);

                $.ajax({
                    url: `{{ url('admin/deposits/edit') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(res) {
                        if (res.success || res.status === 'success') {
                            toastNotification(res.message ||
                                '{{ __('Deposit status updated successfully.') }}',
                                'success');
                            closeModal('edit-modal');
                            $submitBtn.text(originalText).prop('disabled', false);

                            // Update the page content via ajax
                            $.ajax({
                                url: window.location.href,
                                type: 'GET',
                                success: function(html) {
                                    $('#deposit-view-content').html(
                                        $(html).find('#deposit-view-content')
                                        .html()
                                    );
                                }
                            });
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

            // Delete Logic
            $(document).on('click', '.btn-delete-deposit', function() {
                const id = $(this).data('id');
                $('#delete-deposit-id').val(id);
                openModal('delete-modal');
            });

            $('#confirm-delete').on('click', function() {
                const id = $('#delete-deposit-id').val();
                const $btn = $(this);
                const originalText = $btn.text();

                $btn.text('{{ __('Deleting...') }}').prop('disabled', true);

                $.ajax({
                    url: `{{ url('admin/deposits/delete') }}/${id}`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        if (res.success) {
                            window.location.href = "{{ route('admin.deposits.index') }}";
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
