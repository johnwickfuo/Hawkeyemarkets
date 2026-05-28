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

        {{-- Page contents starts here --}}
        <div class="flex-1 p-6 md:p-10 lg:p-12 space-y-8 overflow-y-auto custom-scrollbar">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/5 pb-8">
                <div>
                    <h2 class="text-3xl font-bold text-white tracking-tight uppercase">
                        {{ __('Livechat & Scripts') }}
                    </h2>
                    <p class="text-slate-400 mt-2 font-medium opacity-80">
                        {{ __('Manage your external integrations, header and footer scripts.') }}
                    </p>
                </div>
            </div>

            <form action="{{ route('admin.settings.livechat.update') }}" method="POST" class="space-y-8 ajax-form">
                @csrf

                {{-- Live Chat Scripts --}}
                <div
                    class="p-8 rounded-[2rem] bg-white/5 border border-white/10 relative group/card transition-all duration-500 hover:border-accent-primary/30">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-accent-primary/10 flex items-center justify-center text-accent-primary shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white tracking-tight uppercase">
                                    {{ __('Live Chat Scripts') }}</h3>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-widest mt-1">
                                    {{ __('Third-party chat widgets') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider ml-1">
                                {{ __('Paste Live Chat Script (e.g., Tawk.to, Crisp, Intercom)') }}
                            </label>
                            <textarea name="livechat_scripts" rows="6"
                                class="w-full bg-secondary/40 border border-white/10 rounded-2xl px-6 py-4 text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-accent-primary/50 focus:border-accent-primary/50 transition-all font-mono text-sm custom-scrollbar"
                                placeholder="<!-- Paste your live chat code here -->">{{ $scripts['livechat_scripts'] }}</textarea>
                            <p class="text-[11px] text-slate-500 italic ml-1">
                                {{ __('This script will be rendered just before the closing </body> tag.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Header Scripts --}}
                <div
                    class="p-8 rounded-[2rem] bg-white/5 border border-white/10 relative group/card transition-all duration-500 hover:border-accent-primary/30">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white tracking-tight uppercase">{{ __('Header Scripts') }}
                                </h3>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-widest mt-1">
                                    {{ __('Analytics, pixels, and meta tags') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider ml-1">
                                {{ __('Additional Scripts to Include in <head>') }}
                            </label>
                            <textarea name="header_scripts" rows="6"
                                class="w-full bg-secondary/40 border border-white/10 rounded-2xl px-6 py-4 text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all font-mono text-sm custom-scrollbar"
                                placeholder="<!-- Google Analytics, Facebook Pixel, etc. -->">{{ $scripts['header_scripts'] }}</textarea>
                            <p class="text-[11px] text-slate-500 italic ml-1">
                                {{ __('These scripts will be rendered inside the <head> section of all pages.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer Scripts --}}
                <div
                    class="p-8 rounded-[2rem] bg-white/5 border border-white/10 relative group/card transition-all duration-500 hover:border-accent-primary/30">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover/card:opacity-100 transition-opacity duration-500 rounded-[2rem] pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shadow-inner">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2 1m0 0L10 4m2 1v2.5M4 7l2-1M4 7l2 1M4 7v2.5M10 21l2-1m0 0l2 1m-2-1v-2.5M20 14l-2 1m2-1l-2-1m2 1v2.5M14 17l-2 1m0 0l-2-1m2 1v2.5M4 14l2-1m-2 1l2 1m-2-1v2.5" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white tracking-tight uppercase">
                                    {{ __('Footer Scripts') }}</h3>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-widest mt-1">
                                    {{ __('Custom JS and tracking scripts') }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider ml-1">
                                {{ __('Additional Scripts before </body>') }}
                            </label>
                            <textarea name="footer_scripts" rows="6"
                                class="w-full bg-secondary/40 border border-white/10 rounded-2xl px-6 py-4 text-slate-200 placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all font-mono text-sm custom-scrollbar"
                                placeholder="<!-- Custom JavaScript, etc. -->">{{ $scripts['footer_scripts'] }}</textarea>
                            <p class="text-[11px] text-slate-500 italic ml-1">
                                {{ __('These scripts will be rendered just before the closing </body> tag on all pages.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-4 pt-4 pb-12">
                    <button type="submit"
                        class="px-10 py-4 rounded-2xl bg-accent-primary text-white font-bold uppercase tracking-widest text-sm shadow-lg shadow-accent-primary/25 hover:shadow-accent-primary/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        {{ __('Save Settings') }}
                    </button>
                </div>
            </form>
        </div>
    @endsection
