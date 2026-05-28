@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <style>
        #config-modal select option {
            background-color: #111111 !important;
            color: #ffffff !important;
        }
    </style>
@endpush

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row backdrop-blur-xl min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar (Global Navigator) --}}
        <div
            class="w-full lg:w-80 bg-secondary/60 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col relative group">
            <div
                class="absolute inset-0 bg-gradient-to-b from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
            </div>
            <div class="relative z-10 flex-1 py-8 px-6 lg:px-8 overflow-y-auto custom-scrollbar">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-10 px-6 md:px-10 lg:px-12 overflow-y-auto custom-scrollbar relative">
            <div class="flex flex-col xl:flex-row gap-0 xl:gap-12 min-h-full">

                {{-- Global Actions --}}
                <div class="flex flex-wrap gap-3 mb-8 xl:absolute xl:top-10 xl:right-12 z-20">
                    <button onclick="clearSystemCache()"
                        class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs font-bold text-white uppercase tracking-widest hover:bg-accent-primary hover:border-accent-primary transition-all flex items-center gap-2 group">
                        <svg class="w-3.5 h-3.5 text-slate-500 group-hover:text-white transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Clear Cache') }}
                    </button>
                    <button onclick="openConfigModal()"
                        class="px-5 py-2.5 bg-accent-primary rounded-xl text-xs font-bold text-white uppercase tracking-widest hover:scale-[1.05] active:scale-95 transition-all flex items-center gap-2 shadow-lg shadow-accent-primary/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('Configure') }}
                    </button>
                </div>


                {{-- Local Sub-Navigation --}}
                <div class="w-full xl:w-64 shrink-0 flex flex-col" id="system-tabs-nav">
                    <div class="pb-6 border-b border-white/5 mb-6 hidden xl:block">
                        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] opacity-40">
                            {{ __('Diagnostic Layers') }}</h3>
                    </div>

                    <div class="relative space-y-1" id="tabs-container">
                        {{-- Sliding Indicator --}}
                        <div id="tab-indicator"
                            class="absolute left-0 w-full bg-white/5 rounded-2xl transition-all duration-300 pointer-events-none hidden xl:block border border-white/5">
                        </div>

                        <button data-section="core"
                            class="tab-btn active relative z-10 w-full py-4 px-4 flex items-center gap-4 text-left transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-[.active]:text-accent-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span
                                class="text-xs font-bold text-slate-400 group-[.active]:text-white uppercase tracking-widest transition-colors">{{ __('Platform Core') }}</span>
                        </button>

                        <button data-section="environment"
                            class="tab-btn relative z-10 w-full py-4 px-4 flex items-center gap-4 text-left transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-[.active]:text-accent-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span
                                class="text-xs font-bold text-slate-400 group-[.active]:text-white uppercase tracking-widest transition-colors">{{ __('Environment') }}</span>
                        </button>

                        <button data-section="modules"
                            class="tab-btn relative z-10 w-full py-4 px-4 flex items-center gap-4 text-left transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-[.active]:text-accent-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                            <span
                                class="text-xs font-bold text-slate-400 group-[.active]:text-white uppercase tracking-widest transition-colors">{{ __('PHP Extensions') }}</span>
                        </button>

                        <button data-section="storage"
                            class="tab-btn relative z-10 w-full py-4 px-4 flex items-center gap-4 text-left transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-[.active]:text-accent-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <span
                                class="text-xs font-bold text-slate-400 group-[.active]:text-white uppercase tracking-widest transition-colors">{{ __('Folder Permission') }}</span>
                        </button>

                        <button data-section="infrastructure"
                            class="tab-btn relative z-10 w-full py-4 px-4 flex items-center gap-4 text-left transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-[.active]:text-accent-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span
                                class="text-xs font-bold text-slate-400 group-[.active]:text-white uppercase tracking-widest transition-colors">{{ __('Required Files') }}</span>
                        </button>

                        <button data-section="functions"
                            class="tab-btn relative z-10 w-full py-4 px-4 flex items-center gap-4 text-left transition-all group">
                            <svg class="w-4 h-4 text-slate-500 group-[.active]:text-accent-primary transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                            <span
                                class="text-xs font-bold text-slate-400 group-[.active]:text-white uppercase tracking-widest transition-colors">{{ __('Required Functions') }}</span>
                        </button>
                    </div>
                </div>

                {{-- Flat List Content (Right Side) --}}
                <div class="flex-1 pb-20 xl:mt-32" id="system-sections-wrapper">

                    {{-- Platform Core Content --}}
                    <div id="core" class="system-section animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="flex flex-col gap-2 mb-8 border-b border-white/5 pb-8">
                            <h2 class="text-3xl font-bold text-white tracking-tight leading-none">{{ __('Platform Core') }}
                            </h2>

                        </div>
                        <div class="divide-y divide-white/5">
                            @foreach ($system_settings_tab['system_information'] as $key => $info)
                                <div class="py-6 flex flex-col md:flex-row md:items-center justify-between group gap-4">
                                    <div class="flex flex-col gap-1">
                                        <h5
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-400 transition-colors">
                                            {{ str_replace('_', ' ', $key) }}</h5>
                                        <div class="flex items-center gap-3">
                                            <p class="text-lg font-bold text-white italic tracking-tighter">
                                                {{ is_array($info) ? $info['current'] : $info }}</p>
                                            @if (is_array($info) && isset($info['status']) && $info['status'])
                                                <div class="text-emerald-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-start md:items-end gap-1">
                                        @if (is_array($info) && isset($info['status']) && !$info['status'])
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-red-500">{{ __('Action Required') }}</span>
                                            <p class="text-[10px] text-slate-400 font-bold tracking-tight">
                                                {{ __('Recommendation: Upgrade to ') }} {{ $info['recommended'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Environment Content --}}
                    <div id="environment"
                        class="system-section hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="flex flex-col gap-2 mb-8 border-b border-white/5 pb-8">
                            <h2 class="text-3xl font-bold text-white tracking-tight leading-none">{{ __('Environment') }}
                            </h2>
                        </div>

                        {{-- Environment & Debug Warnings --}}
                        <div class="flex flex-col gap-4 mb-10">
                            @if (config('app.env') !== 'production')
                                <div
                                    class="p-6 rounded-3xl bg-amber-500/5 border border-amber-500/20 flex flex-col md:flex-row md:items-center gap-6 group">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <h4 class="text-sm font-bold text-white uppercase tracking-widest">
                                            {{ __('Non-Production Environment') }}</h4>
                                        <p class="text-[11px] text-amber-500/80 font-medium leading-relaxed">
                                            {{ __('App is currently running in ') }} <span
                                                class="font-black italic underline uppercase tracking-tighter">{{ config('app.env') }}</span>
                                            {{ __(' mode. Ensure you switch to production for live deployment to ensure maximum performance and security.') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if (config('app.debug'))
                                <div
                                    class="p-6 rounded-3xl bg-red-500/5 border border-red-500/20 flex flex-col md:flex-row md:items-center gap-6 group">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-500 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <h4 class="text-sm font-bold text-white uppercase tracking-widest">
                                            {{ __('Debug Mode Enabled') }}</h4>
                                        <p class="text-[11px] text-red-500/80 font-medium leading-relaxed">
                                            {{ __('Detailed error messages are currently visible to users. This is a ') }}
                                            <span
                                                class="font-black italic underline uppercase tracking-tighter">{{ __('Security Risk') }}</span>
                                            {{ __(' in production environments. Disable debug mode before going live.') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="divide-y divide-white/5">
                            @foreach ($system_settings_tab['server_config'] as $key => $config)
                                <div class="py-6 flex flex-col md:flex-row md:items-center justify-between group gap-4">
                                    <div class="flex flex-col gap-1">
                                        <h5
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-400 transition-colors">
                                            {{ str_replace('_', ' ', $key) }}</h5>
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-lg font-bold {{ $config['status'] ? 'text-white' : 'text-red-400' }} tracking-tight italic">{{ $config['current'] }}</span>
                                            @if ($config['status'])
                                                <div class="text-emerald-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-start md:items-end gap-1">
                                        @if (!$config['status'])
                                            <div
                                                class="flex items-center gap-2 text-red-500 bg-red-500/10 px-3 py-1 rounded-lg border border-red-500/20">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span
                                                    class="text-[9px] font-bold uppercase tracking-widest">{{ __('Below Recommended') }}</span>
                                            </div>
                                            <p class="text-[10px] text-slate-500 font-bold tracking-tight mt-1">
                                                {{ __('Adjust to ') }} {{ $config['recommended'] }}
                                                {{ __(' in php.ini') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PHP Extensions Content --}}
                    <div id="modules"
                        class="system-section hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="flex flex-col gap-2 mb-8 border-b border-white/5 pb-8">
                            <h2 class="text-3xl font-bold text-white tracking-tight leading-none">
                                {{ __('PHP Extensions') }}</h2>

                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-4 py-8">
                            @foreach ($system_settings_tab['php_extension'] as $ext)
                                <div
                                    class="flex items-center justify-between p-4 rounded-xl border {{ $ext['status'] ? 'bg-emerald-500/5 border-emerald-500/10' : 'bg-red-500/5 border-red-500/20' }} group">
                                    <span
                                        class="text-[11px] font-bold {{ $ext['status'] ? 'text-slate-400' : 'text-red-500' }} uppercase tracking-widest">{{ $ext['name'] }}</span>
                                    <div class="{{ $ext['status'] ? 'text-emerald-500' : 'text-red-500' }}">
                                        @if ($ext['status'])
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Folder Permission Content --}}
                    <div id="storage"
                        class="system-section hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="flex flex-col gap-2 mb-8 border-b border-white/5 pb-8">
                            <h2 class="text-3xl font-bold text-white tracking-tight leading-none">
                                {{ __('Folder Permission') }}</h2>

                        </div>
                        <div class="divide-y divide-white/5">
                            @foreach ($system_settings_tab['folder_and_file_permissions'] as $name => $permission)
                                <div class="py-6 flex items-center justify-between group">
                                    <div class="flex flex-col gap-1">
                                        <h5
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-400 transition-colors">
                                            {{ $name }}</h5>
                                        <div class="flex items-center gap-3">
                                            <p class="text-lg font-bold text-white italic tracking-tighter">
                                                {{ $permission['current'] }}</p>
                                            @if ($permission['status'])
                                                <div class="text-emerald-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1">
                                        @if (!$permission['status'])
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-red-500">{{ __('Permission Error') }}</span>
                                            <p
                                                class="text-[9px] text-slate-500 font-bold uppercase tracking-widest italic">
                                                {{ __('Run chmod 775 or 755') }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Required Files Content --}}
                    <div id="infrastructure"
                        class="system-section hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="flex flex-col gap-2 mb-8 border-b border-white/5 pb-8">
                            <h2 class="text-3xl font-bold text-white tracking-tight leading-none">
                                {{ __('Required Files') }}</h2>

                        </div>
                        <div class="divide-y divide-white/5">
                            @foreach ($system_settings_tab['required_files'] as $name => $file)
                                <div class="py-6 flex items-center justify-between group">
                                    <div class="flex flex-col gap-1">
                                        <h5
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-400 transition-colors">
                                            {{ $name }}</h5>
                                        <p class="text-[11px] text-slate-600 font-bold tracking-tight opacity-60 italic">
                                            {{ $file['path'] }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @if ($file['status'])
                                            <div
                                                class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @else
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-red-500">{{ __('Missing File') }}</span>
                                            <div
                                                class="w-8 h-8 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Required Functions Content --}}
                    <div id="functions"
                        class="system-section hidden animate-in fade-in slide-in-from-bottom-4 duration-700">
                        <div class="flex flex-col gap-2 mb-8 border-b border-white/5 pb-8">
                            <h2 class="text-3xl font-bold text-white tracking-tight leading-none">
                                {{ __('Required Functions') }}</h2>
                        </div>
                        <div class="divide-y divide-white/5">
                            @foreach ($system_settings_tab['required_functions'] as $func)
                                <div class="py-6 flex items-center justify-between group">
                                    <div class="flex flex-col gap-1">
                                        <h5
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] group-hover:text-slate-400 transition-colors">
                                            {{ $func['name'] }}</h5>
                                        <p class="text-[11px] text-slate-600 font-bold tracking-tight opacity-60 italic">
                                            {{ __('Core PHP Function') }}</p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        @if ($func['status'])
                                            <div
                                                class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @else
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-widest text-red-500">{{ __('Function Missing') }}</span>
                                            <div
                                                class="w-8 h-8 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- Configuration Modal --}}
    <div id="config-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-[#0A0A0A] p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-lg animate__animated animate__zoomIn">
            <h3 class="text-2xl font-bold text-white uppercase tracking-tight mb-8">{{ __('System Configuration') }}</h3>

            <div class="space-y-6">
                {{-- App Debug --}}
                <div class="flex flex-col gap-3 group">
                    <label
                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest group-focus-within:text-accent-primary transition-colors">{{ __('App Debug') }}</label>
                    <div class="relative">
                        <select id="config-app-debug"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none appearance-none cursor-pointer">
                            <option value="true">{{ __('Enabled') }}</option>
                            <option value="false">{{ __('Disabled') }}</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- App Environment --}}
                <div class="flex flex-col gap-3 group">
                    <label
                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest group-focus-within:text-accent-primary transition-colors">{{ __('Environment') }}</label>
                    <div class="relative">
                        <select id="config-app-env"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none appearance-none cursor-pointer">
                            <option value="local">{{ __('Local') }}</option>
                            <option value="production">{{ __('Production') }}</option>
                            <option value="staging">{{ __('Staging') }}</option>
                            <option value="sandbox">{{ __('Sandbox') }}</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Log Level --}}
                <div class="flex flex-col gap-3 group">
                    <label
                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest group-focus-within:text-accent-primary transition-colors">{{ __('Log Level') }}</label>
                    <div class="relative">
                        <select id="config-log-level"
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none appearance-none cursor-pointer">
                            <option value="debug">{{ __('Debug') }}</option>
                            <option value="info">{{ __('Info') }}</option>
                            <option value="warning">{{ __('Warning') }}</option>
                            <option value="error">{{ __('Error') }}</option>
                            <option value="critical">{{ __('Critical') }}</option>
                        </select>
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="button" onclick="closeModal('config-modal')"
                        class="flex-1 py-4 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="button" onclick="saveSystemConfig()"
                        class="flex-[2] py-4 bg-accent-primary text-white text-xs font-bold uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all shadow-lg shadow-accent-primary/20">{{ __('Save Changes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openConfigModal() {
            const currentDebug = "{{ config('app.debug') ? 'true' : 'false' }}";
            const currentEnv = "{{ config('app.env') }}";
            const currentLogLevel = "{{ config('app.log_level', 'debug') }}";

            $('#config-app-debug').val(currentDebug);
            $('#config-app-env').val(currentEnv);
            $('#config-log-level').val(currentLogLevel);

            $('#config-modal').removeClass('hidden').addClass('flex');
        }

        function closeModal(id) {
            $(`#${id}`).addClass('hidden').removeClass('flex');
        }

        function clearSystemCache() {
            toastNotification("{{ __('Clearing cache...') }}", 'info');
            $.ajax({
                url: "{{ route('admin.settings.system.clear-cache') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Failed to clear cache.') }}",
                        'error');
                }
            });
        }

        function saveSystemConfig() {
            const data = {
                _token: "{{ csrf_token() }}",
                APP_DEBUG: $('#config-app-debug').val(),
                APP_ENV: $('#config-app-env').val(),
                LOG_LEVEL: $('#config-log-level').val()
            };

            toastNotification("{{ __('Updating configuration...') }}", 'info');

            $.ajax({
                url: "{{ route('admin.settings.system.update-env') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('config-modal');
                    // Optional: reload page to reflect changes or stay for SPA feel
                    setTimeout(() => location.reload(), 2000);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message ||
                        "{{ __('Failed to update configuration.') }}", 'error');
                }
            });
        }

        // Tab logic enhancement (if needed, currently using scroll-spy style)
        $(document).ready(function() {
            $('.tab-btn').on('click', function() {
                const section = $(this).data('section');
                document.getElementById(section).scrollIntoView({
                    behavior: 'smooth'
                });
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            /**
             * Update the sliding background position based on active tab
             */
            function updateIndicator() {
                const isMobile = window.innerWidth < 1280;
                if (isMobile) {
                    $('#tab-indicator').css('opacity', 0);
                    return;
                }

                const activeTab = $('.tab-btn.active');
                if (activeTab.length) {
                    const container = $('#tabs-container');
                    const offset = activeTab.position().top;
                    const height = activeTab.outerHeight();

                    $('#tab-indicator').css({
                        'top': offset + 'px',
                        'height': height + 'px',
                        'opacity': 1
                    });
                }
            }

            function syncDisplay() {
                const isMobile = window.innerWidth < 1280; // xl breakpoint

                if (isMobile) {
                    $('.system-section').removeClass('hidden').addClass(
                        'pt-12 first:pt-0 pb-16 border-b border-white/10 last:border-0');
                    $('#system-tabs-nav').addClass('hidden');
                } else {
                    $('#system-tabs-nav').removeClass('hidden');
                    const activeId = $('.tab-btn.active').data('section') || 'core';
                    $('.system-section').addClass('hidden').removeClass(
                        'pt-12 first:pt-0 pb-16 border-b border-white/10 last:border-0');
                    $(`#${activeId}`).removeClass('hidden');
                    updateIndicator();
                }
            }

            $('.tab-btn').on('click', function() {
                const sectionId = $(this).data('section');
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                $('.system-section').addClass('hidden');
                $(`#${sectionId}`).removeClass('hidden');

                updateIndicator();
            });

            $(window).on('resize', function() {
                syncDisplay();
            });

            // Initial positioning
            syncDisplay();
            setTimeout(updateIndicator, 50); // Slight delay to ensure layout is ready
        });
    </script>
@endpush

@push('css')
    <style>
        /* Clean Tab Active State */
        .tab-btn.active span {
            color: white !important;
        }

        .tab-btn.active svg {
            color: rgb(var(--accent-primary-rgb)) !important;
        }
    </style>
@endpush
