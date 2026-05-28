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

        .file-upload-wrapper {
            position: relative;
            width: 100%;
        }

        .file-upload-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-display {
            background: rgba(255, 255, 255, 0.03);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 3;
            transition: all 0.3s ease;
        }

        .file-upload-wrapper:hover .file-upload-display {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent-primary);
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
                        {{ __('Certificate Settings') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium tracking-wide">
                        {{ __('Manage regulatory compliance, authority listings, and public PDF certificates.') }}
                    </p>
                </div>

                <form id="certificate-settings-form" action="{{ route('admin.settings.certificate.update') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-12 pb-24">
                    @csrf

                    {{-- Regulatory Compliance --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('Regulatory Regulators') }}</h3>
                            <button type="button" id="add-regulator-btn"
                                class="text-[10px] font-bold text-accent-primary uppercase tracking-widest hover:underline flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>{{ __('Add Regulator') }}</span>
                            </button>
                        </div>

                        <div id="regulators-container" class="space-y-4">
                            @foreach ($compliance['regulators'] as $regulator)
                                <div class="regulator-item flex items-center gap-4">
                                    <input type="text" name="regulators[]" value="{{ $regulator }}" class="flat-input"
                                        placeholder="{{ __('Enter regulator name...') }}">
                                    <button type="button"
                                        class="remove-regulator-btn text-red-500/50 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            @if (empty($compliance['regulators']))
                                <div class="regulator-item flex items-center gap-4">
                                    <input type="text" name="regulators[]" class="flat-input"
                                        placeholder="{{ __('Enter regulator name...') }}">
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- PDF Certificates --}}
                    <div class="settings-section">
                        <div class="mb-8 border-b border-white/5 pb-4 flex items-center justify-between">
                            <h3 class="text-xl font-medium text-white tracking-wide">{{ __('PDF Certificates') }}</h3>
                            <button type="button" id="add-pdf-btn"
                                class="text-[10px] font-bold text-accent-primary uppercase tracking-widest hover:underline flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>{{ __('Add New File') }}</span>
                            </button>
                        </div>

                        <div id="pdf-container" class="space-y-8">
                            @foreach ($compliance['pdf_certificates'] as $index => $cert)
                                <div class="pdf-item grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-white/5 pb-6">
                                    <div class="flex flex-col gap-2">
                                        <label
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Certificate Name') }}</label>
                                        <input type="text" name="pdf_names[{{ $index }}]"
                                            value="{{ $cert['name'] }}" class="flat-input"
                                            placeholder="{{ __('e.g. Broker License') }}">
                                    </div>
                                    <div class="flex flex-col gap-2 relative">
                                        <div class="flex items-center justify-between">
                                            <label
                                                class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('PDF File') }}</label>
                                            @if ($cert['file'])
                                                <a href="{{ asset('assets/pdf/' . $cert['file']) }}" target="_blank"
                                                    class="text-[10px] text-accent-primary hover:underline">{{ __('View Current') }}</a>
                                            @endif
                                        </div>
                                        <div class="file-upload-wrapper">
                                            <input type="file" name="pdf_files[{{ $index }}]"
                                                class="file-upload-input pdf-file-input">
                                            <div class="file-upload-display">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                </svg>
                                                <span class="file-name">{{ __('Click to upload replacement PDF') }}</span>
                                            </div>
                                        </div>
                                        <button type="button"
                                            class="remove-pdf-btn absolute -right-8 top-1/2 -translate-y-1/2 text-red-500/50 hover:text-red-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-8 mt-12 text-right">
                        <button type="submit" id="submit-btn"
                            class="px-10 py-4 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.15em] rounded-xl hover:bg-accent-secondary active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-3 w-max ml-auto">
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
             * Regulator Management
             */
            $('#add-regulator-btn').on('click', function() {
                const html = `
                    <div class="regulator-item flex items-center gap-4">
                        <input type="text" name="regulators[]" class="flat-input" placeholder="{{ __('Enter regulator name...') }}">
                        <button type="button" class="remove-regulator-btn text-red-500/50 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                `;
                $('#regulators-container').append(html);
            });

            $(document).on('click', '.remove-regulator-btn', function() {
                $(this).closest('.regulator-item').remove();
            });

            /**
             * PDF Management
             */
            let pdfCount = {{ count($compliance['pdf_certificates']) }};

            $('#add-pdf-btn').on('click', function() {
                const html = `
                    <div class="pdf-item grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-white/5 pb-6">
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Certificate Name') }}</label>
                            <input type="text" name="pdf_names[${pdfCount}]" class="flat-input" placeholder="{{ __('e.g. Broker License') }}">
                        </div>
                        <div class="flex flex-col gap-2 relative">
                            <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('PDF File') }}</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="pdf_files[${pdfCount}]" class="file-upload-input pdf-file-input">
                                <div class="file-upload-display">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                    <span class="file-name">{{ __('Click to upload PDF') }}</span>
                                </div>
                            </div>
                            <button type="button" class="remove-pdf-btn absolute -right-8 top-1/2 -translate-y-1/2 text-red-500/50 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                `;
                $('#pdf-container').append(html);
                pdfCount++;
            });

            $(document).on('click', '.remove-pdf-btn', function() {
                $(this).closest('.pdf-item').remove();
            });

            $(document).on('change', '.pdf-file-input', function(e) {
                const fileName = e.target.files[0] ? e.target.files[0].name :
                    '{{ __('Click to upload PDF') }}';
                $(this).siblings('.file-upload-display').find('.file-name').text(fileName);
            });

            /**
             * Form Submission
             */
            $('#certificate-settings-form').on('submit', function(e) {
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
