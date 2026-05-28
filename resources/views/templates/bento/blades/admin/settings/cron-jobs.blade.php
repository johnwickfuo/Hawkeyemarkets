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

        {{-- Settings Content --}}
        <div class="flex-1 p-6 md:p-10 lg:p-12 space-y-8 overflow-y-auto custom-scrollbar">

            {{-- Cron Job Commands --}}
            <div class="bg-secondary p-8 rounded-3xl border border-white/5 relative overflow-hidden group">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                </div>

                <div class="relative z-10">
                    <h3 class="text-xl font-bold text-white mb-2">{{ __('System Cron Command Setup') }}</h3>
                    <p class="text-sm text-slate-400 mb-6">
                        {{ __('Add one of the following commands to your system cron (crontab) or hosting control panel. Pick the one that works best for your environment.') }}
                    </p>

                    <div class="space-y-4">
                        @foreach ($cron_job_curl_commands as $index => $command)
                            @if ($index > 0)
                                <div class="relative py-6 flex items-center gap-4">
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent">
                                    </div>
                                    <div
                                        class="shrink-0 w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center shadow-2xl">
                                        <span
                                            class="text-xs font-black text-slate-400 italic tracking-widest">{{ __('OR') }}</span>
                                    </div>
                                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/10 to-transparent">
                                    </div>
                                </div>
                            @endif
                            <div
                                class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-4 group/code">
                                <code class="flex-1 text-xs lg:text-sm text-emerald-400 font-mono break-all"
                                    id="cron-command-{{ $index }}">{{ $command }}</code>
                                <button onclick="copyToClipboard('cron-command-{{ $index }}')"
                                    class="p-3 bg-white/5 border border-white/10 rounded-xl text-white hover:bg-accent-primary hover:border-accent-primary transition-all shrink-0"
                                    title="{{ __('Copy Command') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex flex-col gap-4 p-6 bg-orange-500/5 border border-orange-500/10 rounded-2xl">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center shrink-0 text-orange-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-xs font-bold text-white uppercase tracking-wider">
                                    {{ __('Critical Configuration Safety') }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium leading-relaxed">
                                    {{ __('Pick ONLY ONE command from the options above. Setting up multiple commands for the same schedule will cause system conflicts and double processing of transactions.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Local Dev Tip --}}
                        <div class="mt-4 p-5 bg-[#0B0F17]/60 border border-accent-primary/20 rounded-2xl flex items-center justify-between gap-6 group/dev relative overflow-hidden transition-all duration-500 hover:border-accent-primary/50">
                            <div class="absolute inset-0 bg-gradient-to-r from-accent-primary/5 to-transparent opacity-0 group-hover/dev:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
                            <div class="flex items-center gap-4 relative z-10">
                                <div class="w-10 h-10 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary shadow-inner">
                                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-black text-white uppercase tracking-[0.2em]">{{ __('Developer Mode') }}</span>
                                        <span class="w-1 h-1 rounded-full bg-accent-primary animate-pulse"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-slate-400 font-medium">{{ __('Local Instance:') }}</span>
                                        <code class="text-[11px] text-accent-primary font-mono font-bold" id="local-cron-cmd">php artisan lozand:initiate-cron</code>
                                    </div>
                                </div>
                            </div>
                            <button onclick="copyToClipboard('local-cron-cmd')" class="group/copy flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 border border-white/10 text-slate-400 hover:text-accent-primary hover:bg-accent-primary/10 hover:border-accent-primary/30 transition-all duration-300 relative z-10" title="{{ __('Copy Developer Command') }}">
                                <svg class="w-4 h-4 transition-transform group-hover/copy:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cron Health List --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white tracking-tight">{{ __('Task Health Monitor') }}</h3>
                    <span
                        class="px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        {{ count($cron_health) }} {{ __('Active Tasks') }}
                    </span>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach ($cron_health as $job)
                        @php
                            $isHealthy = time() - $job->last_run <= $job->recommended;
                            $statusColor = $isHealthy ? 'text-emerald-400' : 'text-red-400';
                            $statusBg = $isHealthy
                                ? 'bg-emerald-500/5 border-emerald-500/10'
                                : 'bg-red-500/5 border-red-500/10';
                            $statusIcon = $isHealthy
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
                        @endphp

                        @if ($job->module && !moduleEnabled($job->module))
                            @continue
                        @endif


                        <div
                            class="bg-secondary/40 border {{ $statusBg }} rounded-2xl p-4 lg:p-6 group relative overflow-hidden transition-all hover:bg-white/[0.02]">
                            <div
                                class="absolute inset-0 bg-gradient-to-r {{ $isHealthy ? 'from-emerald-500/[0.02]' : 'from-red-500/[0.02]' }} to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>

                            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-8">
                                {{-- Status indicator --}}
                                <div class="flex items-center gap-3 shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-xl {{ $isHealthy ? 'bg-emerald-500/10' : 'bg-red-500/10' }} flex items-center justify-center {{ $statusColor }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            {!! $statusIcon !!}
                                        </svg>
                                    </div>
                                    <div class="lg:hidden">
                                        <span class="text-[9px] font-bold tracking-widest uppercase {{ $statusColor }}">
                                            {{ $isHealthy ? __('Healthy') : __('Warning') }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Command --}}
                                <div class="flex-1 min-w-0">
                                    <h4
                                        class="text-sm font-bold text-white truncate font-mono opacity-80 group-hover:opacity-100 transition-opacity">
                                        {{ $job->command }}
                                    </h4>
                                </div>

                                @php
                                    $seconds = $job->recommended;
                                    $intervalText = '';
                                    if ($seconds < 60) {
                                        $intervalText = $seconds . ' ' . __('sec');
                                    } elseif ($seconds < 3600) {
                                        $intervalText = round($seconds / 60) . ' ' . __('min');
                                    } else {
                                        $intervalText = round($seconds / 3600) . ' ' . __('hour');
                                    }
                                @endphp

                                {{-- Details --}}
                                <div class="grid grid-cols-2 lg:flex lg:items-center gap-6 lg:gap-12 text-[10px] shrink-0">
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-slate-500 font-bold uppercase tracking-widest">{{ __('Recommended Interval') }}</span>
                                        <span class="text-slate-300 font-bold">{{ $intervalText }}</span>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span
                                            class="text-slate-500 font-bold uppercase tracking-widest">{{ __('Last Execution') }}</span>
                                        <span class="font-bold {{ $statusColor }} italic">
                                            {{ \Carbon\Carbon::createFromTimestamp($job->last_run)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Desktop Status Badge --}}
                                <div class="hidden lg:block shrink-0 min-w-[100px] text-right">
                                    <span
                                        class="px-3 py-1 rounded-lg {{ $isHealthy ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500' }} text-[9px] font-bold tracking-widest uppercase">
                                        {{ $isHealthy ? __('Healthy') : __('Warning') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                toastNotification("{{ __('Command copied to clipboard!') }}", 'success');
            }).catch(err => {
                toastNotification("{{ __('Failed to copy command.') }}", 'error');
            });
        }
    </script>
@endsection
