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

        .flat-textarea {
            min-height: 120px;
            resize: none;
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
            background-color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        input:checked+.slider:before {
            transform: translateX(24px);
            background-color: white;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .file-upload-btn {
            position: absolute;
            z-index: 10;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-btn:hover {
            background: var(--accent-primary);
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
                        {{ __('SEO Configuration') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium tracking-wide">
                        {{ __('Optimize your platform visibility across search engines and social media networks.') }}
                    </p>
                </div>

                <form id="seo-settings-form" action="{{ route('admin.settings.seo.update') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-12 pb-24">
                    @csrf

                    {{-- Search Engine Visibility --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Indexing Policy') }}</h3>
                        </div>

                        <div class="p-6 bg-white/5 rounded-2xl border border-white/5 flex items-center justify-between">
                            <div class="flex flex-col gap-1">
                                <span class="text-sm font-bold text-white">{{ __('Search Engine Indexing') }}</span>
                                <span
                                    class="text-[11px] text-slate-500">{{ __('Allow search engines like Google and Bing to crawl and index your site.') }}</span>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="search_engine_indexing"
                                    {{ $seo['search_engine_indexing'] ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    {{-- Global SEO Metadata --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Search Meta Data') }}</h3>
                        </div>

                        <div class="space-y-8">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('SEO Description') }}</label>
                                <textarea name="seo_description" class="flat-input flat-textarea"
                                    placeholder="{{ __('A brief description of your site for search engine results...') }}">{{ $seo['description'] }}</textarea>
                                <p class="text-[10px] text-slate-500 mt-1 italic">
                                    {{ __('Recommended length: 150-160 characters.') }}</p>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Keywords') }}</label>
                                <input type="text" name="seo_keywords" value="{{ $seo['keywords'] }}" class="flat-input"
                                    placeholder="{{ __('e.g. investment, finance, stocks, crypto') }}">
                                <p class="text-[10px] text-slate-500 mt-1 italic">
                                    {{ __('Comma-separated list of relevant keywords.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Social Media Presence --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Social Graph (OpenGraph)') }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            <div class="flex flex-col gap-8">
                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Social Sharing Title') }}</label>
                                    <input type="text" name="social_title" value="{{ $seo['social_title'] }}"
                                        class="flat-input" placeholder="{{ __('Title for shared links...') }}">
                                </div>
                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Social Sharing Description') }}</label>
                                    <textarea name="social_description" class="flat-input h-32 resize-none"
                                        placeholder="{{ __('Brief summary for shared links...') }}">{{ $seo['social_description'] }}</textarea>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Social Preview Image') }}</label>
                                <div class="image-preview" id="imagePreviewContainer">
                                    @if ($seo['seo_image'])
                                        <img src="{{ asset('assets/images/' . $seo['seo_image']) }}" id="previewTag">
                                    @else
                                        <div class="flex flex-col items-center gap-2 text-slate-500" id="placeholderIcon">
                                            <svg class="w-12 h-12 opacity-20" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest">{{ __('No Preview Image') }}</span>
                                        </div>
                                    @endif
                                    <label class="file-upload-btn">
                                        {{ __('Change Image') }}
                                        <input type="file" name="seo_image" class="hidden" id="imageInput">
                                    </label>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 italic">
                                    {{ __('Recommended size: 1200x630px (Open Graph Standard).') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Social Media Profiles --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Social Profiles') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                            @foreach ($seo['social_media'] as $platform => $url)
                                <div class="flex flex-col gap-3">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ str_replace('_', ' ', $platform) }}</label>
                                    <input type="text" name="social_media[{{ $platform }}]"
                                        value="{{ $url }}" class="flat-input text-xs font-medium"
                                        placeholder="https://{{ $platform }}.com/yourpage">
                                </div>
                            @endforeach
                        </div>
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
        $(document).ready(function() {
            /**
             * Image Preview
             */
            $('#imageInput').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if ($('#previewTag').length) {
                            $('#previewTag').attr('src', e.target.result);
                        } else {
                            $('#placeholderIcon').hide();
                            $('#imagePreviewContainer').prepend(
                                `<img src="${e.target.result}" id="previewTag">`);
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });

            /**
             * Form Submission
             */
            $('#seo-settings-form').on('submit', function(e) {
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
                $btnText.text('{{ __('Synchronizing...') }}');
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
