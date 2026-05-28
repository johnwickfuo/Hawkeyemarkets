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

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
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
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
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
            transform: translateX(24px);
            background-color: white;
        }

        .lang-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .lang-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .lang-card.disabled {
            opacity: 0.5;
            filter: grayscale(0.5);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
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
                        {{ __('Utility Settings') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium tracking-wide">
                        {{ __('Manage platform-wide configurations, system behaviors, and localized experiences.') }}
                    </p>
                </div>

                <form id="utility-settings-form" action="{{ route('admin.settings.utility.update') }}" method="POST"
                    class="space-y-12 pb-24">
                    @csrf

                    {{-- General Configuration --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('General System') }}</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-bold text-white">{{ __('Global Pagination') }}</span>
                                    <span
                                        class="text-[11px] text-slate-500">{{ __('Define how many items per page should be displayed in administrative and user tables.') }}</span>
                                </div>
                                <div>
                                    <input type="number" name="pagination" value="{{ $pagination }}" class="flat-input"
                                        min="1" max="100">
                                </div>
                            </div>

                            <div class="p-6 bg-white/5 rounded-2xl border border-white/5 flex items-center justify-between">
                                <div class="flex flex-col gap-1">
                                    <span
                                        class="text-sm font-bold text-white">{{ __('Delete Notification Messages') }}</span>
                                    <span
                                        class="text-[11px] text-slate-500">{{ __('Delete opened system notification messages to free up database and speed') }}</span>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="delete_notification_message" value="enabled"
                                        {{ $delete_notification_message === 'enabled' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                                <input type="hidden" name="delete_notification_message" value="disabled"
                                    id="delete_notification_hidden">
                            </div>

                            <div class="p-6 bg-white/5 rounded-2xl border border-white/5 flex items-center justify-between">
                                <div class="flex flex-col gap-1">
                                    <span class="text-sm font-bold text-white">{{ __('Dashboard Preloader') }}</span>
                                    <span
                                        class="text-[11px] text-slate-500">{{ __('Toggle the visibility of the initial dashboard preloader animation.') }}</span>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="preloader" value="enabled"
                                        {{ $preloader === 'enabled' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                                <input type="hidden" name="preloader" value="disabled" id="preloader_hidden">
                            </div>
                        </div>
                    </div>

                    {{-- Language Management --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Localized Experience') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($all_languages as $code => $lang)
                                <div class="lang-card {{ in_array($code, $enabled_languages) ? '' : 'disabled' }}"
                                    id="lang-card-{{ $code }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden border border-white/10">
                                            <img loading="lazy" decoding="async" src="{{ asset('assets/flags/' . $lang['flag'] . '.svg') }}"
                                                alt="{{ $lang['name'] }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-white">{{ $lang['name'] }}</span>
                                            <span class="text-[10px] text-slate-500 uppercase tracking-tighter">
                                                {{ $code }} @if ($lang['rtl'])
                                                    • RTL
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" name="enabled_languages[]" value="{{ $code }}"
                                            {{ in_array($code, $enabled_languages) ? 'checked' : '' }}
                                            onchange="toggleLangCard('{{ $code }}')">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-8">
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
                            <span class="btn-text">{{ __('Update Settings') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleLangCard(code) {
            const card = document.getElementById(`lang-card-${code}`);
            if (card) {
                card.classList.toggle('disabled');
            }
        }

        $(document).ready(function() {
            /**
             * Form Submission
             */
            $('#utility-settings-form').on('submit', function(e) {
                e.preventDefault();

                // Handle checkboxes
                if ($('input[name="delete_notification_message"]:checked').length) {
                    $('#delete_notification_hidden').prop('disabled', true);
                } else {
                    $('#delete_notification_hidden').prop('disabled', false);
                }

                if ($('input[name="preloader"]:checked').length) {
                    $('#preloader_hidden').prop('disabled', true);
                } else {
                    $('#preloader_hidden').prop('disabled', false);
                }

                const $form = $(this);
                const $btn = $('#submit-btn');
                const $btnText = $btn.find('.btn-text');
                const $submitIcon = $btn.find('.submit-icon');
                const $loadingIcon = $btn.find('.loading-icon');
                const originalText = $btnText.text();

                const formData = new FormData(this);

                // UI State: Loading
                $btn.prop('disabled', true).addClass('opacity-70 cursor-not-allowed');
                $btnText.text('{{ __('Updating Utilities...') }}');
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
