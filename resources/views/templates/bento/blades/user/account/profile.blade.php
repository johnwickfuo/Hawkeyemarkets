@extends('templates.bento.blades.layouts.user')

@push('css')
    <style>
        .profile-container {
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

        .flat-select {
            background-color: rgba(15, 23, 42, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.25rem;
            padding: 1.25rem 1.5rem;
            color: #fff;
            font-weight: 500;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
            appearance: none;
            cursor: pointer;
        }

        .flat-select:focus {
            border-color: var(--color-accent-primary);
            background: rgba(15, 23, 42, 0.5);
        }

        .flat-select option {
            background-color: #0f172a;
            color: #fff;
            padding: 1rem;
        }

        .image-preview-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            border-radius: 2.5rem;
            overflow: hidden;
            border: 3px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .image-preview-wrapper:hover {
            border-color: var(--color-accent-primary);
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
        }

        .image-upload-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-preview-wrapper:hover .image-upload-overlay {
            opacity: 1;
        }

        .premium-btn {
            background: linear-gradient(135deg, var(--color-accent-primary), var(--color-accent-glow));
            color: white;
            font-weight: 700;
            border-radius: 1.25rem;
            padding: 1.25rem 2.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .premium-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
            filter: brightness(1.1);
        }

        .premium-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 640px) {
            .profile-container {
                padding: 1.5rem;
                border-radius: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="py-10">
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
                @include('templates.bento.blades.user.account.partials.sidebar')
            </div>

            {{-- Right Content --}}
            <div class="lg:col-span-9">
                <div class="profile-container">
                    <form id="profile-update-form" action="{{ route('user.account.profile.update') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-10">
                        @csrf

                        {{-- Profile Image --}}
                        <div class="flex flex-col items-center sm:items-start gap-6">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                {{ __('Profile Picture') }}
                            </label>
                            <div class="flex flex-col sm:flex-row items-center gap-8">
                                <div class="image-preview-wrapper">
                                    @if ($user->photo)
                                        <img id="image-preview" src="{{ asset('storage/profile/' . $user->photo) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div id="image-placeholder"
                                            class="w-full h-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-4xl font-bold">
                                            {{ substr($user->first_name, 0, 1) }}
                                        </div>
                                        <img id="image-preview" class="w-full h-full object-cover hidden">
                                    @endif
                                    <label for="image-upload" class="image-upload-overlay">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </label>
                                </div>
                                <div class="flex flex-col gap-2 text-center sm:text-left">
                                    <p class="text-sm text-white font-medium">{{ __('Upload a new photo') }}</p>
                                    <p class="text-xs text-slate-500">{{ __('Allowed JPG, PNG. Max size 2MB.') }}</p>
                                    <input type="file" id="image-upload" name="photo" class="hidden" accept="image/*">
                                </div>
                            </div>
                        </div>

                        {{-- Form Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            {{-- Username --}}
                            @php
                                $hasUsername = !empty($user->getRawOriginal('username'));
                            @endphp
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('Username') }}
                                </label>
                                <input type="text" name="username" value="{{ $user->getRawOriginal('username') }}"
                                    class="flat-input {{ $hasUsername ? 'opacity-50 cursor-not-allowed' : '' }} text-base"
                                    placeholder="{{ __('Set your username') }}" {{ $hasUsername ? 'readonly' : '' }}>
                                @if ($hasUsername)
                                    <p class="text-[10px] text-slate-500 italic pl-1">
                                        {{ __('Username cannot be changed once set.') }}</p>
                                @endif
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('First Name') }}
                                </label>
                                <input type="text" name="first_name" value="{{ $user->getRawOriginal('first_name') }}"
                                    class="flat-input opacity-50 cursor-not-allowed text-base"
                                    placeholder="{{ __('Enter your first name') }}" readonly>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('Last Name') }}
                                </label>
                                <input type="text" name="last_name" value="{{ $user->getRawOriginal('last_name') }}"
                                    class="flat-input opacity-50 cursor-not-allowed text-base"
                                    placeholder="{{ __('Enter your last name') }}" readonly>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('Email Address') }}
                                </label>
                                <input type="email" name="email" value="{{ $user->getRawOriginal('email') }}"
                                    class="flat-input opacity-50 cursor-not-allowed text-base"
                                    placeholder="{{ __('Enter your email') }}" readonly>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest pl-1">
                                    {{ __('Language') }}
                                </label>
                                <div class="relative">
                                    <select name="lang" class="flat-select text-base">
                                        @foreach (config('languages') as $code => $lang)
                                            <option value="{{ $code }}"
                                                {{ $user->lang == $code ? 'selected' : '' }}>
                                                {{ $lang['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-8 border-t border-white/5">
                            <button type="submit" id="submit-btn" class="premium-btn cursor-pointer">
                                <svg class="w-5 h-5 submit-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <svg class="w-5 h-5 hidden loading-icon animate-spin" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
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
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Image Preview
            $('#image-upload').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result).removeClass('hidden');
                        $('#image-placeholder').addClass('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Form Ajax
            $('#profile-update-form').on('submit', function(e) {
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
                        let errorMessage = xhr.responseJSON ? xhr.responseJSON.message :
                            '{{ __('Something went wrong.') }}';
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join(' | ');
                        }
                        toastNotification(errorMessage, 'error');
                    },
                    complete: function() {
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
