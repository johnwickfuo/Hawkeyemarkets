@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col backdrop-blur-xl min-h-[calc(100vh-160px)]">

        {{-- Header Area --}}
        <div
            class="p-8 md:p-12 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-gradient-to-r from-accent-primary/10 to-transparent">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter mb-2">{{ __('System Update') }}</h2>
                <p class="text-slate-400 text-sm font-medium tracking-tight opacity-70">
                    {{ __('Keep your platform secure and up-to-date with the latest features.') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex flex-col items-end">
                    <span
                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Current Version') }}</span>
                    <span class="text-xl font-black text-white italic tracking-tighter">{{ $current_version }}</span>
                </div>
                <div class="w-px h-8 bg-white/10 mx-2"></div>
                <div class="flex flex-col items-end">
                    <span
                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Latest Version') }}</span>
                    <span
                        class="text-xl font-black {{ $is_update_available ? 'text-accent-primary' : 'text-emerald-500' }} italic tracking-tighter">{{ $latest_version }}</span>
                </div>
            </div>
        </div>

        <div class="flex-1 p-6 md:p-10 lg:p-12 space-y-8 overflow-y-auto custom-scrollbar">

            @if (session('error'))
                <div
                    class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6 flex items-start gap-4 animate__animated animate__shakeX">
                    <div
                        class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-red-500 mb-1">{{ __('Error Encountered') }}</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (!$is_update_available && !isset($error))
                <div
                    class="bg-emerald-500/5 border border-emerald-500/10 rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8 group animate__animated animate__fadeIn">
                    <div
                        class="w-16 h-16 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-500 shrink-0 shadow-2xl group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 flex flex-col gap-2 text-center md:text-left">
                        <h4 class="text-xl font-black text-emerald-500 tracking-tighter uppercase">
                            {{ __('System Up To Date') }}</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed max-w-2xl">
                            {{ __('Great news! You are currently running the latest version of Lozand. Your system is fully optimized and secure.') }}
                        </p>
                    </div>
                    <div class="shrink-0">
                        <div
                            class="px-5 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-500/5 italic">
                            {{ __('No Updates Needed') }}
                        </div>
                    </div>
                </div>
            @endif

            @if ($is_update_available && !isset($error))
                <div
                    class="bg-red-500/5 border border-red-500/10 rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8 group animate__animated animate__pulse animate__infinite animate__slow">
                    <div
                        class="w-16 h-16 rounded-3xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-500 shrink-0 shadow-2xl group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 flex flex-col gap-2 text-center md:text-left">
                        <h4 class="text-xl font-black text-red-500 tracking-tighter uppercase">
                            {{ __('Update Available') }}</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed max-w-2xl">
                            {{ __('A new version :latest is available for download. It is highly recommended to update your system to benefit from the latest improvements and security patches.', ['latest' => $latest_version]) }}
                        </p>
                    </div>
                    <div class="shrink-0 flex flex-col items-center gap-3">
                        <div
                            class="px-5 py-2 rounded-full bg-red-500/10 border border-red-500/20 text-red-500 text-[10px] font-black uppercase tracking-widest shadow-xl shadow-red-500/5 italic">
                            {{ __('Action Required') }}
                        </div>

                    </div>
                </div>
            @endif

            @if (!$php_version_supported)
                <div
                    class="bg-red-500/5 border border-red-500/10 rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8 group animate__animated animate__pulse animate__infinite animate__slow">
                    <div
                        class="w-16 h-16 rounded-3xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-500 shrink-0 shadow-2xl group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 flex flex-col gap-2 text-center md:text-left">
                        <h4 class="text-xl font-black text-red-500 tracking-tighter uppercase">
                            {{ __('Incompatible Environment') }}</h4>
                        <p class="text-xs text-slate-400 font-medium leading-relaxed max-w-2xl">
                            {{ __('The incoming update requires PHP version :required or higher to ensure stability and security. Your server is currently running PHP :current. Please upgrade your environment to proceed.', ['required' => $required_php, 'current' => PHP_VERSION]) }}
                        </p>
                    </div>
                    <div class="shrink-0 flex flex-col items-center gap-3">
                        <div
                            class="px-5 py-2 rounded-full bg-red-500/10 border border-red-500/20 text-red-500 text-[10px] font-black uppercase tracking-widest shadow-xl shadow-red-500/5 italic">
                            {{ __('Update Blocked') }}
                        </div>
                        <button onclick="window.location.reload()"
                            class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold text-white uppercase tracking-widest hover:bg-white/10 hover:border-accent-primary/50 transition-all flex items-center gap-2 group">
                            <svg class="w-3.5 h-3.5 group-hover:rotate-180 transition-transform duration-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            {{ __('Recheck') }}
                        </button>
                    </div>
                </div>
            @endif

            @if (isset($error))
                <div class="bg-red-500/5 border border-red-500/20 rounded-2xl p-6 flex items-start gap-4">
                    <div
                        class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-red-500 mb-1">{{ __('Connection Error') }}</h4>
                        <p class="text-xs text-slate-400 leading-relaxed">{{ $error }}</p>
                        <a href="{{ route('admin.update.index') }}"
                            class="mt-4 inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-accent-primary hover:underline">
                            {{ __('Retry Connection') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                {{-- Update Summary Bento --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    {{-- Left Column: Status & Requirements --}}
                    <div class="xl:col-span-2 space-y-6">

                        {{-- PHP Compatibility --}}
                        <div class="bg-secondary relative border border-white/5 rounded-3xl p-8 overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent pointer-events-none transition-opacity duration-500 group-hover:opacity-100">
                            </div>

                            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="flex items-center gap-6">
                                    <div
                                        class="w-14 h-14 rounded-2xl {{ $php_version_supported ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-red-500/10 text-red-500 border-red-500/20' }} border flex items-center justify-center shrink-0 shadow-2xl">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white tracking-tight">
                                            {{ __('PHP Compatibility') }}</h3>
                                        <p class="text-xs text-slate-500 font-medium">{{ __('Required:') }} <span
                                                class="text-white">{{ $required_php }}</span> {{ __('| Current:') }}
                                            <span
                                                class="{{ $php_version_supported ? 'text-emerald-400' : 'text-red-400' }}">{{ PHP_VERSION }}</span>
                                        </p>
                                    </div>
                                </div>

                                @if (!$php_version_supported)
                                    <div
                                        class="px-5 py-2 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-[10px] font-bold uppercase tracking-widest animate-pulse">
                                        {{ __('Upgrade Required') }}
                                    </div>
                                @else
                                    <div
                                        class="px-5 py-2 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-widest">
                                        {{ __('Compatible') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Pre-update Checks --}}
                        <div class="bg-secondary border border-white/5 rounded-3xl p-8 space-y-8 relative overflow-hidden"
                            id="precheck-section-scroll">
                            <div
                                class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-accent-primary/5 rounded-full blur-3xl opacity-50">
                            </div>

                            <div
                                class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-white/5 pb-6">
                                <div>
                                    <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Pre-update Checks') }}
                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium">
                                        {{ __('Verify server environment and permissions before proceeding.') }}</p>
                                </div>
                                <button id="btn-verify-requirements" onclick="verifyRequirements()"
                                    @if (!$php_version_supported || !$is_update_available) disabled title="{{ !$is_update_available ? __('System is already on the latest version') : __('PHP version :required or higher is required', ['required' => $required_php]) }}" @endif
                                    class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold text-white uppercase tracking-[0.15em] hover:bg-white/10 hover:border-accent-primary/50 transition-all flex items-center gap-2 disabled:opacity-30 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Run Diagnostics') }}
                                </button>
                            </div>

                            <div id="requirements-container"
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 opacity-50 pointer-events-none transition-opacity duration-500">
                                {{-- Placeholder or Loading State if needed --}}
                                <div class="col-span-full py-12 flex flex-col items-center justify-center gap-3">
                                    <svg class="w-8 h-8 text-slate-700 animate-bounce" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                        {{ __('Click Diagnostic button to start') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Change Log --}}
                    <div class="xl:col-span-1 flex flex-col">
                        <div
                            class="bg-secondary border border-white/5 rounded-3xl flex-1 flex flex-col overflow-hidden relative group">
                            <div
                                class="absolute inset-0 bg-gradient-to-b from-accent-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                            </div>

                            <div
                                class="p-8 border-b border-white/5 relative z-10 transition-colors group-hover:border-accent-primary/20">
                                <h3 class="text-lg font-bold text-white tracking-tight flex items-center gap-3">
                                    <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 4v4h4" />
                                    </svg>
                                    {{ __('What\'s New') }}
                                </h3>
                                <div class="mt-2 flex items-center gap-2">
                                    <span
                                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Version') }}</span>
                                    <span
                                        class="px-2 py-0.5 rounded-md bg-accent-primary/10 border border-accent-primary/20 text-accent-primary text-[10px] font-black italic tracking-tighter">{{ $latest_version }}</span>
                                </div>
                            </div>

                            <div class="p-8 flex-1 overflow-y-auto custom-scrollbar relative z-10 max-h-[300px]">
                                <div class="prose prose-invert prose-sm">
                                    @if (isset($update_data['changelog']))
                                        @if (is_array($update_data['changelog']))
                                            <ul class="space-y-4">
                                                @foreach ($update_data['changelog'] as $change)
                                                    <li
                                                        class="relative pl-6 before:content-[''] before:absolute before:left-0 before:top-2 before:w-2 before:h-2 before:bg-accent-primary before:rounded-full">
                                                        {!! $change !!}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {!! $update_data['changelog'] !!}
                                        @endif
                                    @else
                                        <p class="text-slate-500 italic">{{ __('No changelog provided by the server.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="p-8 border-t border-white/5 bg-white/[0.02] relative z-10">
                                <button id="btn-download-updater" onclick="updateUpdater()" disabled
                                    class="w-full py-4 bg-accent-primary disabled:bg-slate-800 disabled:text-slate-500 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold uppercase tracking-[0.2em] rounded-2xl hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-accent-primary/20 flex items-center justify-center gap-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    {{ __('Download Update') }}
                                </button>
                                <p class="text-[9px] text-slate-600 font-bold uppercase tracking-widest text-center mt-4">
                                    {{ __('Complete diagnostic checks to enable') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function verifyRequirements() {
            const btn = $('#btn-verify-requirements');
            const container = $('#requirements-container');
            const downloadBtn = $('#btn-download-updater');

            btn.prop('disabled', true).addClass('opacity-50');
            btn.find('svg').addClass('animate-spin');

            container.removeClass('opacity-50 pointer-events-none').html(`
            <div class="col-span-full py-12 flex flex-col items-center justify-center gap-4">
                <div class="w-10 h-10 border-4 border-accent-primary/20 border-t-accent-primary rounded-full animate-spin"></div>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Verifying server ecosystem...') }}</p>
            </div>
        `);

            $.ajax({
                url: "{{ route('admin.update.verify-requirements') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    renderRequirements(response);
                    if (response.status === 'success') {
                        downloadBtn.prop('disabled', false).removeClass('disabled');
                        downloadBtn.next('p').html("{{ __('System verified. Ready to proceed.') }}").addClass(
                            'text-emerald-500');
                        toastNotification("{{ __('Diagnostics passed. You may proceed with the update.') }}",
                            'success');
                    } else {
                        toastNotification("{{ __('Some requirements are not met.') }}", 'error');
                    }
                },
                error: function(xhr) {
                    toastNotification("{{ __('Diagnostic process failed.') }}", 'error');
                    container.html(
                        `<p class="col-span-full text-center text-red-500 py-8">${xhr.responseJSON?.message || 'Error occurred'}</p>`
                    );
                },
                complete: function() {
                    btn.prop('disabled', false).removeClass('opacity-50');
                    btn.find('svg').removeClass('animate-spin');
                }
            });
        }

        function renderRequirements(data) {
            let html = '';

            // Server Config
            Object.entries(data.server_config).forEach(([key, info]) => {
                html += `
                <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-between group hover:border-accent-primary/20 transition-all">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest group-hover:text-slate-400">${key.replace(/_/g, ' ')}</span>
                        <div class="flex items-center gap-2">
                             <span class="text-sm font-black italic tracking-tighter ${info.status ? 'text-white' : 'text-red-400'}">${info.current}</span>
                             ${!info.status ? `<div class="text-[8px] text-slate-500 font-bold italic tracking-tighter">/ ${info.recommended}</div>` : ''}
                        </div>
                    </div>
                    <div class="w-8 h-8 rounded-lg ${info.status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500'} flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${info.status ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}" />
                        </svg>
                    </div>
                </div>
            `;
            });

            // Extensions
            Object.entries(data.extensions).forEach(([ext, status]) => {
                html += `
                <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/5 flex items-center justify-between group hover:border-accent-primary/20 transition-all">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest group-hover:text-slate-400">${ext}</span>
                        <span class="text-sm font-black italic tracking-tighter ${status ? 'text-white' : 'text-red-400'}">${status ? 'Loaded' : 'Missing'}</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg ${status ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500'} flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="${status ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}" />
                        </svg>
                    </div>
                </div>
            `;
            });

            $('#requirements-container').html(html);
        }

        function updateUpdater() {
            const btn = $('#btn-download-updater');
            btn.prop('disabled', true).addClass('opacity-70');
            btn.find('svg').addClass('animate-bounce');

            toastNotification("{{ __('Contacting central node for update assets...') }}", 'info');

            $.ajax({
                url: "{{ route('admin.update.download-updater') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    setTimeout(() => {
                        window.location.href = response.redirect_url;
                    }, 1500);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Asset acquisition failed.') }}",
                        'error');
                    btn.prop('disabled', false).removeClass('opacity-70');
                    btn.find('svg').removeClass('animate-bounce');
                }
            });
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

        .prose h1,
        .prose h2,
        .prose h3 {
            color: white !important;
            font-weight: 800;
            margin-top: 1.5em;
            margin-bottom: 0.5em;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
        }

        .prose p {
            color: #94a3b8;
            font-size: 0.8125rem;
            line-height: 1.6;
            margin-bottom: 1em;
        }

        .prose ul {
            list-style: none;
            padding-left: 0;
        }

        .prose ul li {
            position: relative;
            padding-left: 1.5rem;
            color: #cbd5e1;
            font-size: 0.8125rem;
            margin-bottom: 0.5em;
        }

        .prose ul li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: rgb(var(--accent-primary-rgb));
            font-weight: 900;
        }
    </style>
@endpush
