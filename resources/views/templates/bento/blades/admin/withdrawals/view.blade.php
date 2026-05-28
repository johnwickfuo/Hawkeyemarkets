@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="withdrawal-view-content" class="space-y-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.withdrawals.index') }}"
                    class="p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-text-secondary hover:text-white transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-tight">{{ __('Withdrawal Details') }}</h1>
                    <p class="text-text-secondary text-sm font-mono mt-1 uppercase tracking-wider">{{ __('Transaction') }}
                        #{{ $withdrawal->transaction_reference }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                        'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                        'failed' => 'bg-red-500/10 text-red-500 border-red-500/20',
                    ];
                    $class =
                        $statusClasses[$withdrawal->status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                @endphp
                <span
                    class="px-4 py-2 rounded-xl border {{ $class }} font-black uppercase tracking-widest text-[11px]">
                    {{ __($withdrawal->status) }}
                </span>

                <button type="button"
                    class="btn-edit-status px-6 py-2 bg-accent-primary hover:bg-accent-primary/90 text-white font-bold rounded-xl transition-all shadow-lg shadow-accent-primary/20 cursor-pointer"
                    data-id="{{ $withdrawal->id }}" data-status="{{ $withdrawal->status }}">
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
                            @if ($withdrawal->user->photo)
                                <div
                                    class="w-12 h-12 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/profile/' . $withdrawal->user->photo) }}"
                                        alt="{{ $withdrawal->user->username }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-12 h-12 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs shrink-0">
                                    {{ substr($withdrawal->user->username, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">
                                    {{ __('Requested By') }}</p>
                                <a href="{{ route('admin.users.detail', $withdrawal->user_id) }}"
                                    class="text-base font-bold text-white hover:text-accent-primary transition-colors cursor-pointer">{{ $withdrawal->user->fullname }}</a>
                                <p class="text-sm text-slate-400 font-mono mt-0.5">{{ $withdrawal->user->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.detail', $withdrawal->user_id) }}"
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
                    <h3 class="text-lg font-bold text-white mb-6 tracking-tight relative z-10">
                        {{ __('Withdrawal Summary') }}
                    </h3>

                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-text-secondary text-sm font-medium">{{ __('Amount Requested') }}</span>
                            <span class="text-white font-mono font-bold">{{ showAmount($withdrawal->amount) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-text-secondary text-sm font-medium">{{ __('Processing Fee') }}
                                <span
                                    class="text-xs text-slate-500 ml-1">({{ number_format($withdrawal->fee_percent, 2) }}%)</span></span>
                            <span class="text-red-400 font-mono font-bold">-
                                {{ showAmount($withdrawal->fee_amount) }}</span>
                        </div>
                        <div
                            class="flex justify-between items-center py-4 border-b border-white/5 bg-white/[0.02] -mx-6 px-6 shadow-inner">
                            <span class="text-white font-bold">{{ __('Net Amount Payable') }}</span>
                            <span
                                class="text-2xl font-black text-emerald-400 font-mono">{{ showAmount($withdrawal->amount_payable) }}</span>
                        </div>

                        @if ($withdrawal->exchange_rate != 1)
                            <div class="bg-[#0F172A] rounded-xl p-5 border border-white/10 mt-4 shadow-lg">
                                <div class="flex justify-between items-center text-sm mb-3">
                                    <span
                                        class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">{{ __('Exchange Rate') }}</span>
                                    <span class="text-white font-mono">1 {{ getSetting('currency') }} =
                                        {{ number_format($withdrawal->exchange_rate, 8) }}
                                        {{ $withdrawal->currency }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-white/5">
                                    <span class="text-white font-bold">{{ __('Converted Amount') }}</span>
                                    <span
                                        class="text-xl font-black text-accent-primary font-mono drop-shadow-[0_0_8px_rgba(var(--color-accent-primary),0.5)]">
                                        {{ number_format($withdrawal->converted_amount, 8) }} {{ $withdrawal->currency }}
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
                    <h3 class="text-lg font-bold text-white mb-6 tracking-tight relative z-10">
                        {{ __('Payment Credentials') }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 relative z-10">
                        <div class="space-y-2 bg-white/5 p-4 rounded-xl border border-white/5">
                            <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                {{ __('Method') }}</p>
                            <p class="text-white font-bold text-base">
                                {{ $withdrawal->withdrawalMethod->name ?? __('Unknown') }}
                            </p>
                        </div>

                        @if ($withdrawal->transaction_hash)
                            <div class="space-y-2 md:col-span-2 bg-white/5 p-4 rounded-xl border border-white/5">
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                    {{ __('Transaction Hash') }}</p>
                                <div class="flex items-center gap-3">
                                    <p class="text-accent-primary font-mono text-sm break-all">
                                        {{ $withdrawal->transaction_hash }}
                                    </p>
                                    <button
                                        class="p-2 bg-black/40 hover:bg-black/80 rounded-lg text-text-secondary hover:text-white transition-colors copy-btn cursor-pointer shrink-0"
                                        data-clipboard-text="{{ $withdrawal->transaction_hash }}"
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
                        $raw_structure = $withdrawal->getAttributes()['structured_data'] ?? null;
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
                                    @continue(is_null($value)) {{-- Skip null values --}}
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
                                {{ __('Requested') }}</p>
                            <p class="text-white font-bold text-sm mt-1.5">{{ $withdrawal->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-slate-500 font-mono text-xs">{{ $withdrawal->created_at->format('H:i:s A') }}
                            </p>
                        </div>

                        @php
                            $dotColor =
                                $withdrawal->status === 'completed'
                                    ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]'
                                    : ($withdrawal->status === 'failed'
                                        ? 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]'
                                        : 'bg-yellow-500 shadow-[0_0_10px_rgba(234,179,8,0.5)]');
                        @endphp
                        <div class="relative">
                            <div
                                class="absolute -left-[27px] top-1 w-4 h-4 rounded-full {{ $dotColor }} ring-4 ring-secondary">
                            </div>
                            <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold">
                                {{ __('Last Check') }}</p>
                            <p class="text-white font-bold text-sm mt-1.5">
                                {{ $withdrawal->updated_at->format('M d, Y') }}</p>
                            <p class="text-slate-500 font-mono text-xs">{{ $withdrawal->updated_at->format('H:i:s A') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="relative group cursor-pointer btn-delete-withdrawal" data-id="{{ $withdrawal->id }}">
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
                                    {{ __('Delete Withdrawal') }}</h4>
                                <p class="text-[10px] text-text-secondary mt-0.5">
                                    {{ __('Permanently remove this record') }}</p>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-text-secondary group-hover:text-red-400 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7-7">
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
                                    @foreach (['pending', 'completed', 'failed'] as $st)
                                        <div class="dropdown-option px-4 py-3 text-sm text-slate-400 hover:bg-white/5 hover:text-white transition-colors cursor-pointer capitalize font-medium"
                                            data-value="{{ $st }}">
                                            {{ ucfirst($st) }}
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
                        {{ __('Are you sure you want to delete this withdrawal? This action cannot be undone and will permanently remove the record from the database.') }}
                    </p>

                    <div class="flex gap-3">
                        <input type="hidden" id="delete-withdrawal-id">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
    <script>
        $(document).ready(function() {
            // Setup Copy to Clipboard
            const clipboard = new ClipboardJS('.copy-btn');
            clipboard.on('success', function(e) {
                toastNotification("{{ __('Copied to clipboard!') }}", 'success');
                e.clearSelection();
            });

            // ── Custom Dropdown functionality ──────────────────────────────────────
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
            $(document).on('click', '.btn-edit-status', function() {
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
                        if (res.success) {
                            // Reload the view content dynamically if possible, or reload page
                            window.location.reload();
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
            $(document).on('click', '.btn-delete-withdrawal', function() {
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
                        if (res.success) {
                            window.location.href = "{{ route('admin.withdrawals.index') }}";
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
