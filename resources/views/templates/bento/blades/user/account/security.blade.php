@extends('templates.bento.blades.layouts.user')

@push('css')
    <style>
        .security-container {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 2.5rem;
            padding: 3.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .flat-input {
            background: rgba(15, 23, 42, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.25rem;
            padding: 1.25rem 1.5rem;
            color: #fff;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            width: 100%;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .flat-input:focus {
            background: rgba(15, 23, 42, 0.5);
            border-color: var(--color-accent-primary);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15), inset 0 2px 4px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .session-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.75rem;
            padding: 1.75rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .session-card:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            transform: scale(1.01);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.3);
        }

        .premium-btn {
            background: linear-gradient(135deg, var(--color-accent-primary), var(--color-accent-glow));
            color: white;
            font-weight: 700;
            border-radius: 1.25rem;
            padding: 1rem 2.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: max-content;
        }

        .premium-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
            filter: brightness(1.1);
        }

        .premium-btn-error {
            background: rgba(var(--color-error), 0.1);
            color: var(--color-error);
            border: 1px solid rgba(var(--color-error), 0.2);
            border-radius: 1rem;
            padding: 0.75rem 1.5rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: all 0.3s ease;
        }

        .premium-btn-error:hover {
            background: var(--color-error);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        @media (max-width: 640px) {
            .security-container {
                padding: 1.5rem;
                border-radius: 1.5rem;
            }

            .session-card {
                padding: 1.25rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="py-10">
        <div class="flex flex-col gap-2 mb-12">
            <h2 class="text-4xl font-light text-white tracking-tight leading-none">
                {{ __('Security Settings') }}
            </h2>
            <p class="text-slate-500 text-sm font-medium tracking-wide">
                {{ __('Secure your account and manage active sessions.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            {{-- Left Sidebar --}}
            <div class="lg:col-span-3">
                @include('templates.bento.blades.user.account.partials.sidebar')
            </div>

            {{-- Right Content --}}
            <div class="lg:col-span-9 space-y-8">
                {{-- Change Password --}}
                <div class="security-container">
                    <div class="flex items-center gap-4 mb-10">
                        <div
                            class="w-12 h-12 rounded-2xl bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Change Password') }}</h3>
                            <p class="text-slate-500 text-sm">{{ __('Update your password regularly to stay secure.') }}</p>
                        </div>
                    </div>

                    {{-- Login Method Info --}}
                    <div class="mb-10 p-6 rounded-2xl bg-white/3 border border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">
                                    {{ __('Logged in via') }}</p>
                                <p class="text-sm text-white font-bold capitalize">{{ $user->provider_name ?? 'Email' }}</p>
                            </div>
                        </div>
                        @if ($user->provider_name)
                            <span
                                class="px-3 py-1 rounded-full bg-accent-primary/20 text-accent-primary text-[10px] font-bold uppercase tracking-widest">
                                {{ __('Social Link') }}
                            </span>
                        @endif
                    </div>

                    <form id="password-update-form" action="{{ route('user.account.password.update') }}" method="POST"
                        class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('Current Password') }}
                                </label>
                                <div class="relative group">
                                    <input type="password" name="current_password" class="flat-input pr-12 text-base"
                                        placeholder="••••••••">
                                    <button type="button"
                                        class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors cursor-pointer">
                                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-2">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('New Password') }}
                                </label>
                                <div class="relative group">
                                    <input type="password" name="password" class="flat-input pr-12 text-base"
                                        placeholder="••••••••">
                                    <button type="button"
                                        class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors cursor-pointer">
                                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('Confirm New Password') }}
                                </label>
                                <div class="relative group">
                                    <input type="password" name="password_confirmation" class="flat-input pr-12 text-base"
                                        placeholder="••••••••">
                                    <button type="button"
                                        class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 hover:text-white transition-colors cursor-pointer">
                                        <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/5">
                            <button type="submit" class="premium-btn cursor-pointer">
                                <span>{{ __('Update Password') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Active Sessions --}}
                <div class="security-container">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Active Sessions') }}</h3>
                                <p class="text-slate-500 text-sm">{{ __('Devices currently logged into your account.') }}
                                </p>
                            </div>
                        </div>

                        <button type="button"
                            onclick="document.getElementById('logout-others-modal').classList.remove('hidden')"
                            class="premium-btn-error cursor-pointer">
                            {{ __('Logout Other Devices') }}
                        </button>
                    </div>

                    <div class="space-y-6">
                        {{-- Current Session --}}
                        <div class="session-card border-accent-primary/20 bg-accent-primary/5">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-white shrink-0">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <p class="text-lg font-bold text-white">{{ request()->ip() }}</p>
                                        @if ($current_location)
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                                            <span
                                                class="text-sm text-slate-400 font-medium">{{ $current_location }}</span>
                                        @endif
                                        <span
                                            class="px-3 py-1 rounded-full bg-accent-primary/20 text-accent-primary text-[10px] font-bold uppercase tracking-widest">
                                            {{ __('Current Session') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 break-words mt-2 font-medium italic opacity-70">
                                        {{ request()->userAgent() }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Other Sessions --}}
                        @forelse($sessions as $session)
                            <div class="session-card">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-slate-500 shrink-0">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="text-lg font-bold text-white">{{ $session->ip_address }}</span>
                                            @if ($session->location)
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span>
                                                <span
                                                    class="text-sm text-slate-400 font-medium">{{ $session->location }}</span>
                                            @endif
                                        </div>
                                        <p
                                            class="text-[11px] text-slate-500 mt-2 font-medium italic break-words opacity-60">
                                            {{ $session->user_agent }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-accent-primary/50"></div>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                                {{ __('Last active:') }} {{ $session->last_activity->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center bg-white/2 rounded-[2rem] border border-dashed border-white/10">
                                <p class="text-slate-500 text-sm font-medium italic">{{ __('No other active sessions.') }}
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Logout Others Modal --}}
    <div id="logout-others-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')">
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
            <div class="bg-secondary-dark rounded-[2.5rem] border border-white/10 shadow-2xl p-10">
                <div
                    class="w-20 h-20 rounded-3xl bg-accent-error/10 flex items-center justify-center text-accent-error mx-auto mb-8">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold text-white text-center mb-3">{{ __('Are you sure?') }}</h3>
                <p class="text-slate-500 text-sm text-center mb-10 leading-relaxed">
                    {{ __('Please enter your current password to confirm logout from all other devices.') }}
                </p>

                <form id="logout-others-form" action="{{ route('user.account.sessions.logout-other') }}" method="POST"
                    class="space-y-8">
                    @csrf
                    <div class="flex flex-col gap-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                            {{ __('Current Password') }}
                        </label>
                        <input type="password" name="password" class="flat-input text-base" placeholder="••••••••">
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="this.closest('#logout-others-modal').classList.add('hidden')"
                            class="flex-1 px-8 py-4 rounded-xl border border-white/10 text-white text-xs font-bold uppercase tracking-[0.15em] hover:bg-white/5 transition-all cursor-pointer">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="flex-1 px-8 py-4 rounded-xl bg-accent-error text-white text-xs font-bold uppercase tracking-[0.15em] hover:bg-red-600 transition-all cursor-pointer">
                            {{ __('Confirm') }}
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
            // Password Toggle
            $('.password-toggle').on('click', function() {
                const input = $(this).siblings('input');
                const eye = $(this).find('.eye-icon');
                const eyeOff = $(this).find('.eye-off-icon');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    eye.addClass('hidden');
                    eyeOff.removeClass('hidden');
                } else {
                    input.attr('type', 'password');
                    eye.removeClass('hidden');
                    eyeOff.addClass('hidden');
                }
            });

            // Password Ajax
            $('#password-update-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');

                $btn.prop('disabled', true).addClass('opacity-70');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        toastNotification(response.message, 'success');
                        $form[0].reset();
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
                    }
                });
            });

            // Logout Others Ajax
            $('#logout-others-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');

                $btn.prop('disabled', true).addClass('opacity-70');

                $.ajax({
                    url: $form.attr('action'),
                    type: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        toastNotification(response.message, 'success');
                        $('#logout-others-modal').addClass('hidden');
                        location.reload();
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
                    }
                });
            });
        });
    </script>
@endpush
