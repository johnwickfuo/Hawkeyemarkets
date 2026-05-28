@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col backdrop-blur-xl min-h-[calc(100vh-160px)]">
        {{-- Custom Header --}}
        <div class="px-8 py-6 border-b border-white/5 flex items-center justify-between bg-white/5">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.file-manager.index') }}"
                    class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex flex-col">
                    <h2 class="text-xl font-black text-white tracking-tight">{{ __('Code Editor') }}</h2>
                    <div class="flex items-center gap-2">
                        <span
                            class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">{{ __('File:') }}</span>
                        <code
                            class="text-[10px] text-accent-primary font-black bg-accent-primary/10 px-2 py-0.5 rounded">{{ basename($path) }}</code>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div id="save-status" class="text-[10px] font-bold text-slate-500 uppercase tracking-widest hidden">
                    <span class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                        {{ __('Saving...') }}
                    </span>
                </div>
                <button type="button" id="save-btn"
                    class="px-8 py-3 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-accent-primary/20 hover:scale-[1.05] active:scale-95 transition-all flex items-center gap-3 group">
                    <span class="btn-text">{{ __('Save Changes') }}</span>
                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Editor Container --}}
        <div class="flex-1 relative min-h-[600px]" id="editor-container">
            <div id="monaco-editor" class="absolute inset-0"></div>

            {{-- Loading Overlay --}}
            <div id="editor-loading"
                class="absolute inset-0 bg-secondary/80 backdrop-blur-sm flex flex-col items-center justify-center z-50 transition-opacity duration-500">
                <div
                    class="w-12 h-12 border-4 border-accent-primary/20 border-t-accent-primary rounded-full animate-spin mb-4">
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest animate-pulse">
                    {{ __('Initializing Monaco...') }}</p>
            </div>
        </div>

        {{-- Metadata Footer --}}
        <div class="px-8 py-3 border-t border-white/5 bg-white/5 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">{{ __('Language:') }}</span>
                    <span id="lang-label" class="text-[9px] text-emerald-500 font-black uppercase">...</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] text-slate-600 font-bold uppercase tracking-widest">{{ __('Path:') }}</span>
                    <span
                        class="text-[9px] text-slate-400 font-medium italic opacity-50 truncate max-w-[400px]">{{ $path }}</span>
                </div>
            </div>
            <div class="text-[9px] text-white/20 font-black italic select-none">
                Lozand Code Engine v1.0
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.js"></script>
    <script>
        $(document).ready(function() {
            let editor;
            const filePath = @json($path);
            const fileExt = filePath.split('.').pop().toLowerCase();

            // Map extensions to Monaco languages
            const langMap = {
                'php': 'php',
                'blade': 'php', // Monaco treats blade largely as php/html
                'js': 'javascript',
                'css': 'css',
                'json': 'json',
                'html': 'html',
                'md': 'markdown'
            };

            const language = langMap[fileExt] || 'plaintext';
            $('#lang-label').text(language === 'php' && filePath.includes('.blade.php') ? 'blade' : language);

            // Load Monaco
            require.config({
                paths: {
                    'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs'
                }
            });

            require(['vs/editor/editor.main'], function() {
                // Define Theme
                monaco.editor.defineTheme('lozand-dark', {
                    base: 'vs-dark',
                    inherit: true,
                    rules: [{
                            token: '',
                            background: '111111'
                        },
                        {
                            token: 'comment',
                            foreground: '6272a4'
                        },
                        {
                            token: 'keyword',
                            foreground: 'ff79c6'
                        },
                        {
                            token: 'string',
                            foreground: 'f1fa8c'
                        },
                        {
                            token: 'variable',
                            foreground: '50fa7b'
                        },
                    ],
                    colors: {
                        'editor.background': '#00000000', // Transparent to show bento background
                        'editor.lineHighlightBackground': '#ffffff05',
                        'editorCursor.foreground': '#10b981',
                        'editor.selectionBackground': '#10b98133',
                        'editorLineNumber.foreground': '#475569',
                        'editorLineNumber.activeForeground': '#10b981',
                    }
                });

                // Initialize Editor
                editor = monaco.editor.create(document.getElementById('monaco-editor'), {
                    value: @json($code),
                    language: language,
                    theme: 'lozand-dark',
                    fontSize: 14,
                    fontFamily: "'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace",
                    lineHeight: 24,
                    minimap: {
                        enabled: true,
                        scale: 0.75
                    },
                    automaticLayout: true,
                    padding: {
                        top: 24,
                        bottom: 24
                    },
                    scrollbar: {
                        vertical: 'visible',
                        horizontal: 'visible',
                        useShadows: false,
                        verticalHasArrows: false,
                        horizontalHasArrows: false,
                        verticalScrollbarSize: 10,
                        horizontalScrollbarSize: 10
                    },
                    roundedSelection: true,
                    cursorSmoothCaretAnimation: "on",
                    cursorBlinking: "smooth",
                    smoothScrolling: true
                });

                // Hide Loading Overlay
                $('#editor-loading').addClass('opacity-0 pointer-events-none');

                // Save Function
                const saveChanges = function() {
                    const $btn = $('#save-btn');
                    const $status = $('#save-status');
                    const code = editor.getValue();

                    $btn.prop('disabled', true).addClass('opacity-50 grayscale cursor-not-allowed');
                    $status.removeClass('hidden');

                    $.ajax({
                        url: "{{ route('admin.file-manager.code-editor.update') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            path: filePath,
                            code: code
                        },
                        success: function(response) {
                            toastNotification("{{ __('File saved successfully!') }}",
                                'success');
                        },
                        error: function(xhr) {
                            let msg = "{{ __('Failed to save file.') }}";
                            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr
                                .responseJSON.message;
                            toastNotification(msg, 'error');
                        },
                        complete: function() {
                            $btn.prop('disabled', false).removeClass(
                                'opacity-50 grayscale cursor-not-allowed');
                            $status.addClass('hidden');
                        }
                    });
                };

                // Bind Clicks
                $('#save-btn').on('click', saveChanges);

                // Bind Shortcuts
                window.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                        e.preventDefault();
                        saveChanges();
                    }
                });
            });
        });
    </script>

    <style>
        .monaco-editor .scroll-decoration {
            box-shadow: none !important;
        }

        .monaco-editor .margin-view-overlays {
            background: transparent !important;
        }

        .monaco-editor .line-numbers {
            font-weight: 700 !important;
            font-size: 10px !important;
        }
    </style>
@endpush
