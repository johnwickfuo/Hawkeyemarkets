@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <style>
        /* Flat Design Adjustments */
        .settings-section {
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .settings-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* Modern Flat Input styling */
        .flat-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 700;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
        }

        .flat-input:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(var(--accent-primary-rgb), 0.5);
        }

        .input-group {
            position: relative;
        }

        .input-group .currency-symbol {
            position: absolute;
            left: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-weight: bold;
            pointer-events: none;
        }

        .input-group.has-symbol input {
            padding-left: 3rem;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.3s ease;
            color: #64748b;
        }

        .tab-btn.active {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .gateway-row {
            padding: 1.25rem 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.25rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .gateway-row:hover {
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        input:checked+.slider {
            background-color: #10b981;
            border-color: #10b981;
        }

        input:checked+.slider:before {
            transform: translateX(20px);
            background-color: white;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar (Global Navigator) --}}
        <div class="w-full lg:w-80 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col pt-8 pr-8">
            <div id="sideBarSelector">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-8 lg:pl-16 overflow-y-auto custom-scrollbar" id="contentScrollContainer">
            <div class="max-w-3xl">
                <div class="flex flex-col gap-2 mb-12">
                    <h2 class="text-4xl font-light text-white tracking-tight leading-none">
                        {{ $page_title }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium tracking-wide">
                        {{ __('Configure platform withdrawal limits, fees, and payment gateway infrastructure.') }}
                    </p>
                </div>

                {{-- Global Configuration Form --}}
                <form id="withdrawal-settings-form" action="{{ route('admin.settings.withdrawal.settings.update') }}"
                    method="POST" class="space-y-12 pb-12">
                    @csrf
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Global Configuration') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Minimum Withdrawal') }}
                                </label>
                                <div class="input-group has-symbol">
                                    <span class="currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                    <input type="number" name="min_withdrawal"
                                        value="{{ $settings['min_withdrawal'] ?? 1 }}" required class="flat-input">
                                </div>
                                <span class="text-[10px] text-slate-600 font-medium">
                                    {{ __('The lowest amount a user can withdrawal.') }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Maximum Withdrawal') }}
                                </label>
                                <div class="input-group has-symbol">
                                    <span class="currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                    <input type="number" name="max_withdrawal"
                                        value="{{ $settings['max_withdrawal'] ?? 6000 }}" required class="flat-input">
                                </div>
                                <span class="text-[10px] text-slate-600 font-medium">
                                    {{ __('The highest amount a user can withdrawal per session.') }}
                                </span>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Withdrawal Fee (%)') }}
                                </label>
                                <input type="number" name="withdrawal_fee" value="{{ $settings['withdrawal_fee'] ?? 5 }}"
                                    step="0.01" required class="flat-input">
                                <span class="text-[10px] text-slate-600 font-medium">
                                    {{ __('System fee applied to every successful withdrawal.') }}
                                </span>
                            </div>

                        </div>

                        {{-- Section Submit Button (at the bottom of form) --}}
                        <div class="pt-10">
                            <button type="submit" id="submit-btn"
                                class="px-10 py-4 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.15em] rounded-xl hover:bg-accent-secondary active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 w-max">
                                <svg class="w-4 h-4 submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <svg class="w-4 h-4 hidden loading-icon animate-spin" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="btn-text">{{ __('Update Global Settings') }}</span>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Payment Gateways Section --}}
                <div class="space-y-12 pb-24">
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Payment Gateways') }}</h3>
                            <div class="flex items-center gap-2 p-1 bg-white/5 rounded-xl">
                                <button onclick="switchTab('manual')" id="tab-manual"
                                    class="tab-btn active text-[10px] uppercase active">{{ __('Manual') }}</button>
                                <button onclick="switchTab('automatic')" id="tab-automatic"
                                    class="tab-btn text-[10px] uppercase">{{ __('Automatic') }}</button>
                            </div>
                        </div>

                        {{-- Manual Gateways Content --}}
                        <div id="content-manual" class="tab-content transition-all duration-300 flex flex-col gap-4">
                            @foreach ($manual_gateways as $gateway)
                                <div class="gateway-row" id="gateway-row-{{ $gateway->id }}">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-xl border border-white/5 bg-white/5 flex items-center justify-center p-2">
                                            <img src="{{ asset('assets/images/withdrawal-methods/' . $gateway->logo) }}"
                                                alt="{{ $gateway->name }}" class="max-w-full max-h-full object-contain">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-white">{{ $gateway->name }}</span>
                                            <div class="flex items-center gap-2 mt-1">
                                                <label class="switch">
                                                    <input type="checkbox" class="status-toggle"
                                                        data-id="{{ $gateway->id }}"
                                                        {{ $gateway->status === 'enabled' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.settings.withdrawal.edit', $gateway->id) }}"
                                            class="px-5 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-widest transition-all">
                                            {{ __('Edit') }}
                                        </a>
                                        <button onclick="openDeleteModal({{ $gateway->id }})"
                                            class="w-10 h-10 flex items-center justify-center rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            <a href="{{ route('admin.settings.withdrawal.create') }}"
                                class="mt-4 w-full py-4 border-2 border-dashed border-white/5 rounded-2xl flex items-center justify-center gap-3 text-slate-500 hover:border-accent-primary hover:text-white transition-all group">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span
                                    class="text-xs font-black uppercase tracking-widest">{{ __('Add New Manual Gateway') }}</span>
                            </a>
                        </div>

                        {{-- Automatic Gateways Content --}}
                        <div id="content-automatic"
                            class="tab-content hidden transition-all duration-300 flex flex-col gap-4">
                            {{-- Support Message Card --}}
                            <div
                                class="relative overflow-hidden p-6 rounded-3xl bg-gradient-to-br from-accent-primary/10 via-transparent to-transparent border border-white/5 group">
                                <div
                                    class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <svg class="w-24 h-24 text-accent-primary" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z" />
                                    </svg>
                                </div>
                                <div class="relative z-10 flex flex-col gap-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-accent-primary/20 flex items-center justify-center text-accent-primary">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-sm font-bold text-white uppercase tracking-widest">
                                            {{ __('Need a specific gateway?') }}</h4>
                                    </div>
                                    <p class="text-slate-400 text-xs font-medium leading-relaxed max-w-md">
                                        {{ __('Missing a payment gateway you need? Contact us and we will add it within 48 hours for free. No additional charges.') }}
                                    </p>
                                    <a href="https://lozand.com/contact" target="_blank"
                                        class="flex items-center gap-2 text-accent-primary text-[10px] font-black uppercase tracking-widest hover:text-white transition-colors w-max group/link">
                                        {{ __('Contact Support') }}
                                        <svg class="w-3 h-3 transform group-hover/link:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @foreach ($automatic_gateways as $gateway)
                                <div class="gateway-row" id="gateway-row-{{ $gateway->id }}">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-xl border border-white/5 bg-white/5 flex items-center justify-center p-2">
                                            <img src="{{ asset('assets/images/withdrawal-methods/' . $gateway->logo) }}"
                                                alt="{{ $gateway->name }}" class="max-w-full max-h-full object-contain">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-white">{{ $gateway->name }}</span>
                                            <div class="mt-1">
                                                <label class="switch">
                                                    <input type="checkbox" class="status-toggle"
                                                        data-id="{{ $gateway->id }}"
                                                        {{ $gateway->status === 'enabled' ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.settings.withdrawal.edit', $gateway->id) }}"
                                        class="px-5 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-widest transition-all">
                                        {{ __('Configure') }}
                                    </a>
                                </div>
                            @endforeach

                            @if ($automatic_gateways->isEmpty())
                                <div
                                    class="py-12 text-center bg-white/[0.01] border border-dashed border-white/5 rounded-2xl">
                                    <p class="text-slate-600 font-bold uppercase tracking-widest text-[10px]">
                                        {{ __('No Automatic Providers Configured') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-[#0a0a0b] border border-white/10 rounded-3xl w-full max-w-md p-8 shadow-2xl animate-in fade-in zoom-in duration-300">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">{{ __('Delete Gateway') }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-8">
                    {{ __('Are you sure you want to delete this payment gateway? This action cannot be undone and may affect pending transactions.') }}
                </p>
                <div class="flex flex-col w-full gap-3">
                    <button id="confirmDeleteBtn"
                        class="w-full py-4 bg-red-500 text-white text-xs font-bold uppercase tracking-widest rounded-2xl hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                        <span class="btn-text">{{ __('Delete Now') }}</span>
                        <svg class="hidden w-4 h-4 loading-icon animate-spin" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="closeModal('deleteModal')"
                        class="w-full py-4 bg-white/5 text-white text-xs font-bold uppercase tracking-widest rounded-2xl hover:bg-white/10 transition-all">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function switchTab(tab) {
            // Update Buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.getElementById(`tab-${tab}`).classList.add('active');

            // Update Content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('flex');
            });

            const activeContent = document.getElementById(`content-${tab}`);
            activeContent.classList.remove('hidden');
            activeContent.classList.add('flex', 'animate-in', 'fade-in', 'slide-in-from-bottom-2', 'duration-300');
        }

        $(document).ready(function() {
            /**
             * Form Submission
             */
            $('#withdrawal-settings-form').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const $btn = $('#submit-btn');
                const $btnText = $btn.find('.btn-text');
                const $submitIcon = $btn.find('.submit-icon');
                const $loadingIcon = $btn.find('.loading-icon');
                const originalText = $btnText.text();

                const formData = new FormData(this);

                // UI State: Loading
                $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
                $btnText.text('{{ __('Saving...') }}');
                $submitIcon.addClass('hidden');
                $loadingIcon.removeClass('hidden');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastNotification(response.message, 'success');
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON.message ||
                            '{{ __('Something went wrong. Please check your inputs.') }}';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join(' | ');
                        }
                        toastNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        // Reset UI State
                        $btn.prop('disabled', false).removeClass(
                            'opacity-70 cursor-not-allowed');
                        $btnText.text(originalText);
                        $submitIcon.removeClass('hidden');
                        $loadingIcon.addClass('hidden');
                    }
                });
            });

            /**
             * Status Toggle Submission
             */
            $(document).on('change', '.status-toggle', function() {
                const $toggle = $(this);
                const id = $toggle.data('id');
                const isChecked = $toggle.is(':checked');

                $.ajax({
                    url: '{{ route('admin.settings.withdrawal.toggle-status') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function(response) {
                        toastNotification(response.message, 'success');
                    },
                    error: function(xhr) {
                        // Revert toggle if error
                        $toggle.prop('checked', !isChecked);
                        let errorMessage = xhr.responseJSON.message ||
                            '{{ __('Something went wrong. Please check your inputs.') }}';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join(' | ');
                        }
                        toastNotification(errorMessage, 'error');
                    }
                });
            });
        });

        let gatewayIdToDelete = null;

        function openDeleteModal(id) {
            gatewayIdToDelete = id;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            gatewayIdToDelete = null;
        }

        // Handle deletion execution
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!gatewayIdToDelete) return;

            const $btn = $(this);
            const $btnText = $btn.find('.btn-text');
            const $loadingIcon = $btn.find('.loading-icon');
            const originalText = $btnText.text();

            // UI State: Loading
            $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
            $btnText.text('{{ __('Deleting...') }}');
            $loadingIcon.removeClass('hidden');

            $.ajax({
                url: `{{ url('admin/settings/withdrawal/delete') }}/${gatewayIdToDelete}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    const deletedId = gatewayIdToDelete;
                    closeModal('deleteModal');
                    $(`#gateway-row-${deletedId}`).fadeOut(400, function() {
                        $(this).remove();

                        // Check if list is empty for manual
                        if ($('#content-manual').find('.gateway-row').length === 0) {
                            // Optionally add a "no gateways" message here if needed
                        }
                    });
                },
                error: function(xhr) {
                    let errorMessage = '{{ __('Failed to delete gateway.') }}';
                    if (xhr.status === 403 || xhr.status === 422) {
                        errorMessage = xhr.responseJSON.message || errorMessage;
                    }
                    toastNotification(errorMessage, 'error');
                    $btn.prop('disabled', false).removeClass('opacity-70 cursor-not-allowed');
                    $btnText.text(originalText);
                    $loadingIcon.addClass('hidden');
                }
            });
        });
    </script>
@endsection
