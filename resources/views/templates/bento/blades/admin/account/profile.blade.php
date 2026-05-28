@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <style>
        .profile-container {
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

        .flat-select {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
            appearance: none;
        }

        .flat-select option {
            background-color: #0f172a;
            color: #fff;
        }

        .image-preview-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 2rem;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .image-preview-wrapper:hover {
            border-color: var(--accent-primary);
        }

        .image-upload-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .image-preview-wrapper:hover .image-upload-overlay {
            opacity: 1;
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
                <div class="profile-container">
                    <form id="profile-update-form" action="{{ route('admin.account.profile.update') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-10">
                        @csrf

                        {{-- Profile Image --}}
                        <div class="flex flex-col items-center sm:items-start gap-6">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                {{ __('Profile Picture') }}
                            </label>
                            <div class="flex items-center gap-8">
                                <div class="image-preview-wrapper">
                                    @if ($admin->image)
                                        <img id="image-preview" src="{{ asset('storage/profile/' . $admin->image) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div id="image-placeholder"
                                            class="w-full h-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-4xl font-bold">
                                            {{ substr($admin->name, 0, 1) }}
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
                                <div class="flex flex-col gap-2">
                                    <p class="text-sm text-white font-medium">{{ __('Upload a new photo') }}</p>
                                    <p class="text-xs text-slate-500">{{ __('Allowed JPG, PNG. Max size 2MB.') }}</p>
                                    <input type="file" id="image-upload" name="image" class="hidden" accept="image/*">
                                </div>
                            </div>
                        </div>

                        {{-- Form Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Full Name') }}
                                </label>
                                <input type="text" name="name" value="{{ $admin->name }}"
                                    class="flat-input text-base" placeholder="{{ __('Enter your name') }}">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Username') }}
                                </label>
                                <input type="text" name="username" value="{{ $admin->username }}"
                                    class="flat-input text-base" placeholder="{{ __('Enter your username') }}">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Email Address') }}
                                </label>
                                <input type="email" name="email" value="{{ $admin->email }}"
                                    class="flat-input text-base" placeholder="{{ __('Enter your email') }}">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ __('Language') }}
                                </label>
                                <select name="lang" class="flat-select">
                                    @foreach (config('languages') as $code => $lang)
                                        <option value="{{ $code }}" {{ $admin->lang == $code ? 'selected' : '' }}>
                                            {{ $lang['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-8 border-t border-white/5">
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
