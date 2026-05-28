@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <style>
        .security-container {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
            padding: 3rem;
        }

        .flat-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
        }

        .flat-input:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(var(--accent-primary-rgb), 0.5);
            box-shadow: 0 0 0 4px rgba(var(--accent-primary-rgb), 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .eye-toggle {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .eye-toggle:hover {
            color: white;
        }

        .session-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
            padding: 1rem;
            transition: all 0.3s ease;
        }

        @media (min-width: 640px) {
            .session-card {
                padding: 1.5rem;
            }
        }

        .session-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: rgba(255, 255, 255, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="max-w-7xl mx-auto py-12">
        <div class="flex flex-col gap-2 mb-12">
            <h2 class="text-4xl font-light text-white tracking-tight leading-none">
                {{ __('Account Settings') }}
            </h2>
            <p class="text-slate-500 text-sm font-medium tracking-wide">
                {{ __('Manage your profile and security settings.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            {{-- Left Sidebar --}}
            <div class="lg:col-span-3">
                @include("templates.$template.blades.admin.account.partials.sidebar")
            </div>

            {{-- Right Content --}}
            <div class="lg:col-span-9">
                <div class="space-y-12">
                    {{-- Password Update Section --}}
                    <div class="security-container">
                        <div class="mb-8 pb-4 border-b border-white/5">
                            <h3 class="text-xl font-medium text-white tracking-wide">
                                {{ __('Change Password') }}
                            </h3>
                        </div>

                        <form id="password-update-form" action="{{ route('admin.account.password.update') }}" method="POST"
                            class="space-y-10">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                                <div class="flex flex-col gap-3 md:col-span-2">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Current Password') }}
                                    </label>
                                    <div class="password-wrapper">
                                        <input type="password" name="current_password" class="flat-input pr-12 text-base"
                                            placeholder="{{ __('Enter current password') }}">
                                        <span class="eye-toggle" onclick="togglePasswordVisibility(this)">
                                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('New Password') }}
                                    </label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password" class="flat-input pr-12 text-base"
                                            placeholder="{{ __('Enter new password') }}">
                                        <span class="eye-toggle" onclick="togglePasswordVisibility(this)">
                                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ __('Confirm Password') }}
                                    </label>
                                    <div class="password-wrapper">
                                        <input type="password" name="password_confirmation"
                                            class="flat-input pr-12 text-base"
                                            placeholder="{{ __('Repeat new password') }}">
                                        <span class="eye-toggle" onclick="togglePasswordVisibility(this)">
                                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-white/5">
                                <button type="submit" id="password-submit-btn"
                                    class="px-10 py-4 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.15em] rounded-xl hover:bg-accent-secondary active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 w-max">
                                    <svg class="w-4 h-4 submit-icon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <svg class="w-4 h-4 hidden loading-icon animate-spin" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span class="btn-text">{{ __('Update Password') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Sessions Section --}}
                    <div class="security-container">
                        <div class="mb-8 pb-4 border-b border-white/5 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">
                                {{ __('Active Sessions') }}
                            </h3>
                            @if ($sessions->count() > 0)
                                <button onclick="openLogoutModal()"
                                    class="text-[10px] font-bold text-accent-error uppercase tracking-widest hover:underline">
                                    {{ __('Logout other devices') }}
                                </button>
                            @endif
                        </div>

                        <div class="space-y-4">
                            {{-- Current Session --}}
                            <div class="session-card border-accent-primary/20 bg-accent-primary/5">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full overflow-hidden">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-white shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0 overflow-hidden">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <p class="text-sm font-bold text-white">{{ request()->ip() }}</p>
                                                @if ($current_location)
                                                    <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-600"></span>
                                                    <span
                                                        class="text-xs text-slate-400 font-medium">{{ $current_location }}</span>
                                                @endif
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-accent-primary/20 text-accent-primary text-[10px] font-bold uppercase tracking-wider">
                                                    {{ __('Current Session') }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 break-words mt-1 italic">
                                                {{ request()->userAgent() }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Other Sessions --}}
                            @forelse($sessions as $session)
                                <div class="session-card">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 w-full overflow-hidden">
                                        <div
                                            class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-slate-500 shrink-0">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0 overflow-hidden">
                                            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                                <span
                                                    class="font-bold text-white sm:font-normal sm:text-slate-500">{{ $session->ip_address }}</span>
                                                @if ($session->location)
                                                    <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-600"></span>
                                                    <span
                                                        class="text-slate-400 font-medium">{{ $session->location }}</span>
                                                @endif
                                            </div>
                                            <p
                                                class="text-[10px] text-slate-600 mt-1 font-medium italic break-words w-full">
                                                {{ $session->user_agent }}
                                            </p>
                                            <p class="text-[10px] text-slate-600 mt-1 font-medium">
                                                {{ __('Last active:') }} {{ $session->last_activity->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Logout Modal --}}
    <div id="logout-modal"
        class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-secondary-dark border border-white/10 rounded-3xl p-8 max-w-md w-full shadow-2xl">
            <h3 class="text-xl font-bold text-white mb-4">{{ __('Logout Other Devices') }}</h3>
            <p class="text-slate-400 text-sm mb-6">
                {{ __('Please enter your current password to confirm logging out from all other devices.') }}
            </p>
            <form id="logout-others-form" action="{{ route('admin.account.sessions.logout-other') }}" method="POST"
                class="space-y-6">
                @csrf
                <div class="flex flex-col gap-3">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                        {{ __('Current Password') }}
                    </label>
                    <input type="password" name="password" class="flat-input text-base" required>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeLogoutModal()"
                        class="flex-1 py-4 bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" id="logout-submit-btn"
                        class="flex-1 py-4 bg-accent-error text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                        <span class="btn-text">{{ __('Logout Others') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function togglePasswordVisibility(el) {
            const input = el.previousElementSibling;
            const eyeIcon = el.querySelector('svg');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                input.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        function openLogoutModal() {
            $('#logout-modal').removeClass('hidden');
        }

        function closeLogoutModal() {
            $('#logout-modal').addClass('hidden');
        }

        $(document).ready(function() {
            // Password Update Ajax
            $('#password-update-form').on('submit', function(e) {
                e.preventDefault();
                const $btn = $('#password-submit-btn');
                const $btnText = $btn.find('.btn-text');
                const $submitIcon = $btn.find('.submit-icon');
                const $loadingIcon = $btn.find('.loading-icon');
                const originalText = $btnText.text();

                $btn.prop('disabled', true).addClass('opacity-70');
                $btnText.text('{{ __('Updating...') }}');
                $submitIcon.addClass('hidden');
                $loadingIcon.removeClass('hidden');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        toastNotification(response.message, 'success');
                        $('#password-update-form')[0].reset();
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            '{{ __('Something went wrong.') }}';
                        if (xhr.status === 422) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join(
                                ' | ');
                        }
                        toastNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).removeClass('opacity-70');
                        $btnText.text(originalText);
                        $submitIcon.removeClass('hidden');
                        $loadingIcon.addClass('hidden');
                    }
                });
            });

            // Logout Others Ajax
            $('#logout-others-form').on('submit', function(e) {
                e.preventDefault();
                const $btn = $('#logout-submit-btn');
                const $btnText = $btn.find('.btn-text');
                const originalText = $btnText.text();

                $btn.prop('disabled', true).addClass('opacity-70');
                $btnText.text('{{ __('Processing...') }}');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        toastNotification(response.message, 'success');
                        setTimeout(() => location.reload(), 1500);
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            '{{ __('Something went wrong.') }}';
                        toastNotification(errorMessage, 'error');
                        $btn.prop('disabled', false).removeClass('opacity-70');
                        $btnText.text(originalText);
                    }
                });
            });
        });
    </script>
@endpush
