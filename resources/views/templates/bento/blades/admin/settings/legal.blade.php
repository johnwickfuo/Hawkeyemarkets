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

        {{-- Legal Content --}}
        <div class="flex-1 p-6 md:p-10 lg:p-12 space-y-8 overflow-y-auto custom-scrollbar">


            {{-- Disclaimer Sections --}}
            <div class="space-y-6">
                {{-- Software Demonstration Notice --}}
                <div class="py-6 border-b border-white/5 last:border-0">
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white tracking-tight uppercase">
                                {{ __('System Demonstration') }}</h3>
                        </div>
                        <p class="text-slate-400 font-medium leading-relaxed opacity-90">
                            {{ __('Lozand Architects developed this software as a high-fidelity demonstration of institutional financial infrastructure and custom engine architecture. It is designed to illustrate the capabilities, visual aesthetics, and complex workflows of a live system operating with integrated custom engines.') }}
                        </p>
                    </div>
                </div>

                {{-- Full Indemnification --}}
                <div class="py-6 border-b border-white/5 last:border-0">
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.153-2.05-.438-3.016z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white tracking-tight uppercase">
                                {{ __('Full Indemnification & Liability') }}</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 border-l-4 border-l-red-500">
                                <p
                                    class="text-slate-300 font-bold leading-relaxed opacity-90 uppercase tracking-widest text-xs">
                                    {{ __('The Lozand Team and its architects are hereby fully indemnified from any and all damages, financial losses, regulatory penalties, or legal repercussions arising from the purchaser\'s choice of usage, deployment strategies, or operational decisions.') }}
                                </p>
                            </div>
                            <p class="text-slate-400 font-medium leading-relaxed opacity-80">
                                {{ __('By acquiring and utilizing this software, the purchaser acknowledges that they bear sole responsibility for ensuring the platform\'s usage complies with all applicable local and international financial regulations. Any diversion from the intended demonstration purpose is done at the purchaser\'s exclusive risk.') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Risk Acknowledgment --}}
                <div class="py-6 border-b border-white/5 last:border-0">
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white tracking-tight uppercase">
                                {{ __('Operational Responsibility') }}</h3>
                        </div>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                            <li class="p-4 bg-white/5 rounded-xl flex items-center gap-4">
                                <div
                                    class="w-1.5 h-1.5 rounded-full bg-accent-primary shadow-[0_0_8px_rgba(var(--accent-primary-rgb),1)]">
                                </div>
                                <span
                                    class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ __('Compliance Responsibility') }}</span>
                            </li>
                            <li class="p-4 bg-white/5 rounded-xl flex items-center gap-4">
                                <div
                                    class="w-1.5 h-1.5 rounded-full bg-accent-primary shadow-[0_0_8px_rgba(var(--accent-primary-rgb),1)]">
                                </div>
                                <span
                                    class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ __('Operational Integrity') }}</span>
                            </li>
                            <li class="p-4 bg-white/5 rounded-xl flex items-center gap-4">
                                <div
                                    class="w-1.5 h-1.5 rounded-full bg-accent-primary shadow-[0_0_8px_rgba(var(--accent-primary-rgb),1)]">
                                </div>
                                <span
                                    class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ __('Asset Security') }}</span>
                            </li>
                            <li class="p-4 bg-white/5 rounded-xl flex items-center gap-4">
                                <div
                                    class="w-1.5 h-1.5 rounded-full bg-accent-primary shadow-[0_0_8px_rgba(var(--accent-primary-rgb),1)]">
                                </div>
                                <span
                                    class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ __('Third-Party Integrations') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
