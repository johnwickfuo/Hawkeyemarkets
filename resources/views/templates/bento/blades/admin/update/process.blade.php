@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col backdrop-blur-xl min-h-[calc(100vh-160px)]">

        {{-- Header Area --}}
        <div
            class="p-8 md:p-12 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-gradient-to-r from-accent-primary/10 to-transparent">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span
                        class="px-3 py-1 bg-accent-primary/10 border border-accent-primary/20 text-accent-primary text-[10px] font-bold uppercase tracking-[0.2em] rounded-full">{{ __('Phase 2') }}</span>
                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter">{{ __('Core Installation') }}
                    </h2>
                </div>
                <p class="text-slate-400 text-sm font-medium tracking-tight opacity-70">
                    {{ __('Acquiring and deploying core system assets. Please do not close this window.') }}
                </p>
            </div>

            <div id="status-badge"
                class="px-5 py-2 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-500 text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                {{ __('In Progress') }}
            </div>
        </div>

        <div class="flex-1 p-6 md:p-10 lg:p-12 space-y-10 overflow-y-auto custom-scrollbar">

            {{-- Progress Visuals --}}
            <div class="space-y-6">
                {{-- Progress Bar Container --}}
                <div class="relative pt-1">
                    <div class="flex mb-2 items-center justify-between">
                        <div>
                            <span id="progress-text"
                                class="text-[10px] font-bold inline-block py-1 px-3 uppercase tracking-widest rounded-full text-accent-primary bg-accent-primary/10">
                                {{ __('Initializing') }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span id="progress-percent" class="text-sm font-black text-white italic tracking-tighter">
                                0%
                            </span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-3 mb-4 text-xs flex rounded-full bg-white/5 border border-white/5">
                        <div id="progress-bar" style="width:0%"
                            class="shadow-lg shadow-accent-primary/20 bg-accent-primary transition-all duration-700 ease-out flex flex-col text-center whitespace-nowrap text-white justify-center">
                        </div>
                    </div>
                </div>

                {{-- Terminal Interface --}}
                <div
                    class="bg-[#050505] rounded-3xl border border-white/5 overflow-hidden shadow-2xl group transition-all duration-500 hover:border-accent-primary/30">
                    {{-- Terminal Header --}}
                    <div class="px-6 py-4 bg-white/5 border-b border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500/50"></div>
                            <div class="w-3 h-3 rounded-full bg-amber-500/50"></div>
                            <div class="w-3 h-3 rounded-full bg-emerald-500/50"></div>
                        </div>
                        <span
                            class="text-[9px] font-bold text-slate-500 uppercase tracking-widest font-mono">{{ __('updater_protocol_v2.log') }}</span>
                    </div>

                    {{-- Terminal Log --}}
                    <div id="terminal-log"
                        class="p-8 font-mono text-xs leading-relaxed overflow-y-auto max-h-[400px] custom-scrollbar space-y-2">
                        <div class="text-slate-500">[{{ date('Y-m-d H:i:s') }}] <span class="text-emerald-400">INFO:</span>
                            Update session initialized (UID: {{ $uid }})</div>
                        <div class="text-slate-500">[{{ date('Y-m-d H:i:s') }}] <span class="text-emerald-400">INFO:</span>
                            Awaiting core execution command...</div>
                    </div>
                </div>
            </div>

            <div id="retry-container" class="hidden animate-in fade-in zoom-in-95 duration-700">
                <div
                    class="bg-red-500/5 border border-red-500/20 rounded-[2rem] p-10 flex flex-col items-center text-center gap-6">
                    <div
                        class="w-20 h-20 rounded-3xl bg-red-500/10 border border-red-500/20 shadow-2xl flex items-center justify-center text-red-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white tracking-tighter mb-2">{{ __('Update Interrupted') }}</h3>
                        <p id="error-description" class="text-slate-400 text-sm font-medium leading-relaxed max-w-sm">
                            {{ __('An error occurred during the update process.') }}
                        </p>
                    </div>
                    <button id="btn-retry"
                        class="px-8 py-3 bg-accent-primary border border-accent-primary/20 rounded-2xl text-xs font-bold text-white uppercase tracking-widest hover:brightness-110 transition-all shadow-xl shadow-accent-primary/20 flex items-center gap-3 group">
                        {{ __('Retry Current Step') }}
                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Completion State (Hidden by default) --}}
            <div id="completion-container" class="hidden animate-in fade-in zoom-in-95 duration-700">
                <div
                    class="bg-emerald-500/5 border border-emerald-500/20 rounded-[2rem] p-10 flex flex-col items-center text-center gap-6">
                    <div
                        class="w-20 h-20 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 shadow-2xl flex items-center justify-center text-emerald-500 animate-bounce">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-white tracking-tighter mb-2">
                            {{ __('Platform Updated Successfully') }}</h3>
                        <p class="text-slate-400 text-sm font-medium leading-relaxed max-w-sm">
                            {{ __('All assets have been deployed. The system is re-optimizing. You will be redirected shortly.') }}
                        </p>
                    </div>
                    <a href="{{ route('admin.update.index') }}"
                        class="px-8 py-3 bg-white/5 border border-white/10 rounded-2xl text-xs font-bold text-white uppercase tracking-widest hover:bg-emerald-500 hover:border-emerald-500 transition-all shadow-xl shadow-emerald-500/10 flex items-center gap-3 group">
                        {{ __('Return to Update Center') }}
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const stages = [{
                id: 'init-cleanup',
                name: "{{ __('Initial Cleanup') }}",
                progress: 15
            },
            {
                id: 'download',
                name: "{{ __('Downloading Package') }}",
                progress: 30
            },
            {
                id: 'extract',
                name: "{{ __('Extracting Assets') }}",
                progress: 50
            },
            {
                id: 'sanitize',
                name: "{{ __('Sanitizing Files') }}",
                progress: 65
            },
            {
                id: 'replace',
                name: "{{ __('Deploying Core') }}",
                progress: 85
            },
            {
                id: 'cleanup',
                name: "{{ __('Finalizing Update') }}",
                progress: 100
            }
        ];

        let currentStageIndex = 0;

        $(document).ready(function() {
            // Start the update process automatically
            setTimeout(processNextStage, 1500);

            $('#btn-retry').on('click', function() {
                $('#retry-container').addClass('hidden');
                $('#status-badge').removeClass('bg-red-500/10 text-red-500 border-red-500/20')
                    .addClass('bg-amber-500/10 text-amber-500 border-amber-500/20')
                    .html(
                        `<div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div> {{ __('In Progress') }}`
                    );

                logToTerminal("Retrying stage: " + stages[currentStageIndex].name, "WARN");
                processNextStage();
            });
        });

        function logToTerminal(message, type = 'INFO') {
            const terminal = $('#terminal-log');
            const timestamp = new Date().toISOString().replace('T', ' ').split('.')[0];
            let colorClass = 'text-emerald-400';

            if (type === 'ERROR') colorClass = 'text-red-400';
            if (type === 'WARN') colorClass = 'text-amber-400';
            if (type === 'PROCESS') colorClass = 'text-blue-400';

            terminal.append(`
            <div class="text-slate-500 animate-in fade-in slide-in-from-left-2 duration-300">
                [${timestamp}] <span class="${colorClass}">${type}:</span> ${message}
            </div>
        `);

            terminal.scrollTop(terminal[0].scrollHeight);
        }

        function updateProgressBar(percent, text) {
            $('#progress-bar').css('width', percent + '%');
            $('#progress-percent').text(percent + '%');
            if (text) $('#progress-text').text(text);
        }

        function processNextStage() {
            if (currentStageIndex >= stages.length) {
                handleSuccess();
                return;
            }

            const stage = stages[currentStageIndex];
            updateProgressBar(stage.progress, stage.name);

            $.ajax({
                url: "{{ route('admin.update.process.index') }}/" + stage.id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    uid: "{{ $uid }}"
                },
                beforeSend: function() {
                    logToTerminal(stage.name + "...", "PROCESS");
                },
                success: function(response) {
                    logToTerminal(response.message, "INFO");
                    currentStageIndex++;

                    // Small delay between stages for visual feedback
                    setTimeout(processNextStage, 1000);
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || "Critical failure during " + stage.name;
                    logToTerminal(errorMsg, "ERROR");

                    $('#error-description').text(errorMsg);
                    $('#retry-container').removeClass('hidden');

                    $('#status-badge').removeClass('bg-amber-500/10 text-amber-500 border-amber-500/20')
                        .addClass('bg-red-500/10 text-red-500 border-red-500/20')
                        .html(
                            `<div class="w-2 h-2 rounded-full bg-red-500"></div> {{ __('Execution Failed') }}`
                        );

                    toastNotification(errorMsg, 'error');
                }
            });
        }

        function handleSuccess() {
            $('#status-badge').removeClass('bg-amber-500/10 text-amber-500 border-amber-500/20')
                .addClass('bg-emerald-500/10 text-emerald-500 border-emerald-500/20')
                .html(`<div class="w-2 h-2 rounded-full bg-emerald-500"></div> {{ __('Updated') }}`);

            $('#completion-container').removeClass('hidden');

            // Hide terminal after success for clean look
            setTimeout(() => {
                $('#terminal-log').parent().fadeOut(1000);
                $('.relative.pt-1').fadeOut(1000);
            }, 2000);

            toastNotification("{{ __('System update complete.') }}", 'success');

            setTimeout(() => {
                window.location.href = "{{ route('admin.update.index') }}";
            }, 10000);
        }
    </script>
@endpush

@push('css')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(var(--accent-primary-rgb), 0.2);
            border-radius: 10px;
        }
    </style>
@endpush
