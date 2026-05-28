@extends(auth()->guard('admin')->check() ? 'templates.bento.blades.admin.layouts.admin' : 'templates.bento.blades.layouts.user')

@section('content')
    <div class="relative z-10 flex flex-col items-center justify-center text-center py-20 px-4 min-h-[60vh]">
        {{-- Hero Illustration/Icon --}}
        <div class="relative mb-12">
            <div class="absolute inset-0 bg-accent-primary/20 blur-[80px] rounded-full"></div>
            <div
                class="w-32 h-32 rounded-[2.5rem] bg-secondary-dark/50 border border-white/10 flex items-center justify-center shadow-2xl backdrop-blur-2xl relative group hover:scale-105 transition-transform duration-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-accent-primary animate-pulse" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v4" />
                    <path d="M12 18v4" />
                    <path d="M4.93 4.93l2.83 2.83" />
                    <path d="M16.24 16.24l2.83 2.83" />
                    <path d="M2 12h4" />
                    <path d="M18 12h4" />
                    <path d="M4.93 19.07l2.83-2.83" />
                    <path d="M16.24 7.76l2.83-2.83" />
                </svg>
            </div>
        </div>

        {{-- Main Message --}}
        <div class="max-w-2xl mx-auto">
            <h1 class="font-heading text-4xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight uppercase">
                {{ __('Coming') }} <span class="text-accent-primary">{{ __('Soon') }}</span>
            </h1>
            <p class="text-lg text-text-secondary mb-12 font-medium leading-relaxed max-w-lg mx-auto italic">
                {{ __('This feature is currently under development. Our team is working hard to bring this functionality to you soon.') }}
            </p>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ auth()->guard('admin')->check() ? route('admin.dashboard') : route('user.dashboard') }}"
                    class="px-8 py-4 rounded-2xl bg-accent-primary text-white font-black uppercase tracking-widest text-sm hover:bg-accent-primary/80 transition-all shadow-lg shadow-accent-primary/20 active:scale-95">
                    {{ __('Back to Dashboard') }}
                </a>
                <button onclick="window.history.back()"
                    class="px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest text-sm hover:bg-white/10 transition-all active:scale-95">
                    {{ __('Go Back') }}
                </button>
            </div>
        </div>

        {{-- Debug Info (Local/Staging only) --}}
        @if ($is_local)
            <div class="mt-20 w-full max-w-3xl">
                <div class="bg-secondary-dark/40 border border-white/5 rounded-3xl p-8 backdrop-blur-xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-2 rounded-full bg-accent-error"></div>
                        <h4 class="text-sm font-black text-white uppercase tracking-widest">{{ __('Debug Information') }}
                        </h4>
                    </div>

                    <div class="space-y-4">
                        @if ($controller)
                            <div class="flex flex-col gap-1 text-left">
                                <span
                                    class="text-[10px] text-text-secondary uppercase font-bold tracking-widest opacity-60">
                                    {{ __('Controller') }}
                                </span>
                                <code
                                    class="text-sm text-accent-primary font-mono py-2 px-3 bg-black/30 rounded-lg border border-white/5 break-all">
                                    {{ $controller }}
                                </code>
                            </div>
                        @endif

                        @if ($method)
                            <div class="flex flex-col gap-1 text-left">
                                <span
                                    class="text-[10px] text-text-secondary uppercase font-bold tracking-widest opacity-60">
                                    {{ __('Method') }}
                                </span>
                                <code
                                    class="text-sm text-white font-mono py-2 px-3 bg-black/30 rounded-lg border border-white/5">
                                    {{ $method . '()' }}
                                </code>
                            </div>
                        @endif

                        <div class="flex flex-col gap-1 text-left">
                            <span class="text-[10px] text-text-secondary uppercase font-bold tracking-widest opacity-60">
                                {{ __('Exception Message') }}
                            </span>
                            <p
                                class="text-xs text-text-secondary font-medium py-3 px-4 bg-black/20 rounded-xl border border-white/5 leading-relaxed italic">
                                {{ $message }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between">
                        <span class="text-[10px] text-text-secondary/50 font-medium italic">
                            {{ __('Note: This debug panel is only visible in local and staging environments.') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent-primary"></span>
                            <span class="text-[9px] font-black text-accent-primary uppercase tracking-widest">
                                {{ app()->environment() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
