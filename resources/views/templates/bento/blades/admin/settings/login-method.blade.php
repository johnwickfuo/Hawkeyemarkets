@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row backdrop-blur-xl min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
        <div
            class="w-full lg:w-80 bg-secondary/60 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col relative group">
            <div
                class="absolute inset-0 bg-gradient-to-b from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
            </div>
            <div class="relative z-10 flex-1 py-8 px-6 lg:px-8 overflow-y-auto custom-scrollbar">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Login Methods Content --}}
        <div class="flex-1 p-6 md:p-10 lg:p-12 space-y-8 overflow-y-auto custom-scrollbar">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-white tracking-tight">{{ __('Login Methods') }}</h2>
                    <p class="text-slate-400 mt-2">{{ __('Configure how your users and admins authenticate.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                @foreach ($login_methods as $key => $method)
                    <form action="{{ route('admin.settings.login-method.update') }}" method="POST" class="ajax-form">
                        @csrf
                        <input type="hidden" name="provider" value="{{ $key }}">
                        <input type="hidden" name="status" id="status_{{ $key }}"
                            value="{{ $method['status'] }}">

                        <div class="bg-secondary/40 border border-white/5 rounded-3xl overflow-hidden transition-all duration-300 provider-card"
                            id="card_{{ $key }}">
                            <div class="p-6 md:p-8 flex flex-col gap-6">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center p-3">
                                            {!! $method['icon'] ?? '' !!}
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-white">{{ $method['name'] }}</h3>
                                            <p class="text-slate-400 text-sm mt-1">
                                                {{ $key === 'email' ? __('Traditional email and password authentication.') : __('Authenticate via ' . $method['name'] . ' OAuth.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer provider-toggle"
                                                data-provider="{{ $key }}"
                                                {{ $method['status'] === 'enabled' ? 'checked' : '' }}>
                                            <div
                                                class="w-14 h-7 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent-primary">
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Details Form - Only for social providers --}}
                                @if ($key !== 'email')
                                    <div class="provider-details {{ $method['status'] === 'enabled' ? '' : 'hidden' }}"
                                        id="details_{{ $key }}">
                                        <div class="pt-6 border-t border-white/5">
                                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                                                {{-- Instructions --}}
                                                <div class="space-y-6">
                                                    <div
                                                        class="bg-accent-primary/5 rounded-2xl p-6 border border-accent-primary/10">
                                                        <h4
                                                            class="font-bold text-accent-primary flex items-center gap-2 mb-4">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ __('Setup Instructions') }}
                                                        </h4>
                                                        <ul class="space-y-3 text-sm text-slate-300">
                                                            <li class="flex gap-2">
                                                                <span class="text-accent-primary font-bold">1.</span>
                                                                <span>{{ __('Create a developer application on ') }} <a
                                                                        href="{{ $key === 'google' ? 'https://console.cloud.google.com' : ($key === 'github' ? 'https://github.com/settings/developers' : ($key === 'facebook' ? 'https://developers.facebook.com' : '#')) }}"
                                                                        target="_blank"
                                                                        class="text-accent-primary hover:underline">{{ $method['name'] }}</a></span>
                                                            </li>
                                                            <li class="flex gap-2">
                                                                <span class="text-accent-primary font-bold">2.</span>
                                                                <span>{{ __('Configure the following Redirect URIs:') }}</span>
                                                            </li>
                                                        </ul>

                                                        <div class="mt-4 space-y-3">
                                                            <div>
                                                                <span
                                                                    class="text-xs text-slate-500 uppercase font-bold tracking-wider">{{ __('User Callback') }}</span>
                                                                <div
                                                                    class="flex items-center gap-2 mt-1 p-3 bg-black/40 rounded-xl border border-white/5 group">
                                                                    <code id="user_cb_{{ $key }}"
                                                                        class="text-xs text-teal-400 break-all">{{ url("/login/$key/callback") }}</code>
                                                                    <button type="button"
                                                                        onclick="copyToClipboard('user_cb_{{ $key }}')"
                                                                        class="ml-auto p-1.5 hover:bg-white/10 rounded-lg text-slate-500 hover:text-white transition-colors">
                                                                        <svg class="w-4 h-4" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            @if ($key === 'google')
                                                                <div>
                                                                    <span
                                                                        class="text-xs text-slate-500 uppercase font-bold tracking-wider">{{ __('Admin Callback') }}</span>
                                                                    <div
                                                                        class="flex items-center gap-2 mt-1 p-3 bg-black/40 rounded-xl border border-white/5 group">
                                                                        <code id="admin_cb_{{ $key }}"
                                                                            class="text-xs text-teal-400 break-all">{{ url('/admin/login/google/callback') }}</code>
                                                                        <button type="button"
                                                                            onclick="copyToClipboard('admin_cb_{{ $key }}')"
                                                                            class="ml-auto p-1.5 hover:bg-white/10 rounded-lg text-slate-500 hover:text-white transition-colors">
                                                                            <svg class="w-4 h-4" fill="none"
                                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round" stroke-width="2"
                                                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Form Fields --}}
                                                <div class="space-y-6">
                                                    @php
                                                        $prefix = strtoupper($key);
                                                    @endphp
                                                    <div class="space-y-2">
                                                        <label
                                                            class="text-sm font-bold text-slate-400 uppercase tracking-widest pl-1">{{ __('Client ID') }}</label>
                                                        <div class="relative group">
                                                            <div
                                                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-accent-primary text-slate-500">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                                </svg>
                                                            </div>
                                                            <input type="text" name="env[{{ $prefix }}_CLIENT_ID]"
                                                                value="{{ sandBoxCredentials(config("services.$key.client_id")) }}"
                                                                placeholder="{{ __('Enter client ID') }}"
                                                                class="w-full bg-black/20 border-white/10 focus:border-accent-primary focus:ring-accent-primary/20 text-white text-base placeholder-slate-600 rounded-2xl py-4 pl-14 transition-all duration-300">
                                                        </div>
                                                    </div>

                                                    <div class="space-y-2">
                                                        <label
                                                            class="text-sm font-bold text-slate-400 uppercase tracking-widest pl-1">{{ __('Client Secret') }}</label>
                                                        <div class="relative group password-toggle-container">
                                                            <div
                                                                class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none transition-colors group-focus-within:text-accent-primary text-slate-500">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                                </svg>
                                                            </div>
                                                            <input type="password"
                                                                name="env[{{ $prefix }}_CLIENT_SECRET]"
                                                                value="{{ sandBoxCredentials(config("services.$key.client_secret")) }}"
                                                                placeholder="{{ __('Enter client secret') }}"
                                                                class="w-full bg-black/20 border-white/10 focus:border-accent-primary focus:ring-accent-primary/20 text-white text-base placeholder-slate-600 rounded-2xl py-4 pl-14 transition-all duration-300 password-input">
                                                            <button type="button"
                                                                class="absolute inset-y-0 right-0 pr-5 flex items-center text-slate-500 hover:text-white transition-colors toggle-password">
                                                                <svg class="w-5 h-5 eye-icon" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.076m1.133-1.133A9.969 9.969 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21m-7-7l3 3m-3-3l-3-3m1.272-1.272a3.359 3.359 0 114.746 4.746" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Card Footer with Individual Save Button --}}
                                <div class="flex justify-end mt-4 pt-4 border-t border-white/5">
                                    <button type="submit" data-loading-text="{{ __('Saving...') }}"
                                        class="px-6 py-2.5 bg-accent-primary hover:bg-accent-primary/90 text-white font-bold rounded-xl transition-all shadow-lg shadow-accent-primary/10 flex items-center gap-2 group">
                                        <span>{{ __('Save') }} {{ $method['name'] }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle Provider Toggles
            $('.provider-toggle').on('change', function() {
                const provider = $(this).data('provider');
                const isEnabled = $(this).is(':checked');
                const $details = $('#details_' + provider);
                const $statusInput = $('#status_' + provider);
                const $card = $('#card_' + provider);
                const $form = $(this).closest('form');

                // Update hidden status input
                $statusInput.val(isEnabled ? 'enabled' : 'disabled');

                // Toggle details visibility
                if (isEnabled) {
                    $details.removeClass('hidden').hide().slideDown(300);
                    $card.addClass('ring-2 ring-accent-primary/20');
                } else {
                    $details.slideUp(300, function() {
                        $(this).addClass('hidden');
                    });
                    $card.removeClass('ring-2 ring-accent-primary/20');

                    // Auto-submit when toggling OFF
                    $form.submit();
                }
            });

            // Password Visibility Toggle
            $('.toggle-password').on('click', function() {
                const $container = $(this).closest('.password-toggle-container');
                const $input = $container.find('.password-input');
                const $eyeIcon = $(this).find('.eye-icon');
                const $eyeOffIcon = $(this).find('.eye-off-icon');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $eyeIcon.addClass('hidden');
                    $eyeOffIcon.removeClass('hidden');
                } else {
                    $input.attr('type', 'password');
                    $eyeIcon.removeClass('hidden');
                    $eyeOffIcon.addClass('hidden');
                }
            });

            // Initial state for cards
            $('.provider-toggle').each(function() {
                const provider = $(this).data('provider');
                if ($(this).is(':checked')) {
                    $('#card_' + provider).addClass('ring-2 ring-accent-primary/20');
                }
            });
        });

        // Global Utility for Copy to Clipboard
        function copyToClipboard(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                if (typeof toastNotification === 'function') {
                    toastNotification('{{ __('Copied to clipboard.') }}', 'success');
                } else {
                    alert('{{ __('Copied to clipboard.') }}');
                }
            });
        }
    </script>
@endpush
