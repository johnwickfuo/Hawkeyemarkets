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
            padding-left: 3.5rem;
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

        .level-badge {
            background: rgba(var(--accent-primary-rgb), 0.1);
            color: var(--accent-primary);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
                        {{ __('Bonus System') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium tracking-wide">
                        {{ __('Configure incentives, sign-up bonuses, and multi-level referral rewards.') }}
                    </p>
                </div>

                <form id="bonus-settings-form" action="{{ route('admin.settings.bonus-system.update') }}" method="POST"
                    class="space-y-12 pb-24">
                    @csrf

                    {{-- Welcome Bonus Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Sign-up Incentives') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Welcome Bonus') }}</label>
                                <div class="input-group has-symbol">
                                    <span class="currency-symbol">{{ getSetting('currency_symbol') }}</span>
                                    <input type="number" name="welcome_bonus" value="{{ getSetting('welcome_bonus', 10) }}"
                                        step="0.01" min="0" required class="flat-input">
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 italic">
                                    {{ __('Credits given to new users immediately upon registration.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Multi-Level Referral Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">
                                {{ __('Multi-Level Referral Rewards') }}</h3>
                            <button type="button" id="add-level-btn"
                                class="text-[10px] font-bold text-accent-primary uppercase tracking-widest hover:underline flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>{{ __('Add Level') }}</span>
                            </button>
                        </div>

                        <div id="referral-levels-container" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            @foreach ($referral_bonus as $index => $value)
                                <div class="referral-level-item flex flex-col gap-3" data-level="{{ $index + 1 }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <label
                                                class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Level') }}
                                                <span class="level-num">{{ $index + 1 }}</span></label>
                                            <span class="level-badge">{{ __('Tier') }} <span
                                                    class="level-num">{{ $index + 1 }}</span></span>
                                        </div>
                                        @if ($index > 0)
                                            <button type="button"
                                                class="remove-level-btn text-red-500/50 hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="input-group">
                                        <input type="number" name="referral_bonus[]" value="{{ $value }}"
                                            step="0.01" min="0" required class="flat-input">
                                        <div
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-500">
                                            %
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-slate-500 mt-8 italic max-w-xl">
                            {{ __('Define how much reward a referrer receives for an investment made by their downline. Level 1 is the direct referral, Level 2 is the referral of the referral, and so on.') }}
                        </p>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-8 mt-12">
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
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            /**
             * Dynamic Level Management
             */
            const $container = $('#referral-levels-container');
            const currencySymbol = '%';

            $('#add-level-btn').on('click', function() {
                const nextLevel = $('.referral-level-item').length + 1;
                const html = `
                    <div class="referral-level-item flex flex-col gap-3" data-level="${nextLevel}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Level') }} <span class="level-num">${nextLevel}</span></label>
                                <span class="level-badge">{{ __('Tier') }} <span class="level-num">${nextLevel}</span></span>
                            </div>
                            <button type="button" class="remove-level-btn text-red-500/50 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                        <div class="input-group">
                            <input type="number" name="referral_bonus[]" value="0"
                                step="0.01" min="0" required class="flat-input">
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-500">
                                ${currencySymbol}
                            </div>
                        </div>
                    </div>
                `;
                $container.append(html);
            });

            $(document).on('click', '.remove-level-btn', function() {
                $(this).closest('.referral-level-item').remove();
                // Renumber
                $('.referral-level-item').each(function(index) {
                    const level = index + 1;
                    $(this).attr('data-level', level);
                    $(this).find('.level-num').text(level);
                });
            });

            /**
             * Form Submission
             */
            $('#bonus-settings-form').on('submit', function(e) {
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
        });
    </script>
@endpush
