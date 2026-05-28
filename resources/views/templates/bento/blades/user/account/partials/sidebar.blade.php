<div class="flex flex-col gap-3">
    <a href="{{ route('user.account.profile') }}"
        class="flex items-center gap-4 px-6 py-5 rounded-[1.75rem] border transition-all duration-300 {{ request()->routeIs('user.account.profile') ? 'bg-accent-primary/10 border-accent-primary/20 text-white shadow-lg shadow-accent-primary/5' : 'bg-white/3 border-white/5 text-slate-400 hover:bg-white/5 hover:text-white' }}">
        <div
            class="w-11 h-11 rounded-2xl {{ request()->routeIs('user.account.profile') ? 'bg-accent-primary/20 text-accent-primary' : 'bg-white/5 text-slate-500' }} flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold leading-tight">{{ __('Profile Details') }}</p>
            <p class="text-[10px] opacity-50 font-medium mt-1 uppercase tracking-wider">{{ __('Personal info') }}</p>
        </div>
    </a>

    <a href="{{ route('user.account.security') }}"
        class="flex items-center gap-4 px-6 py-5 rounded-[1.75rem] border transition-all duration-300 {{ request()->routeIs('user.account.security') ? 'bg-accent-primary/10 border-accent-primary/20 text-white shadow-lg shadow-accent-primary/5' : 'bg-white/3 border-white/5 text-slate-400 hover:bg-white/5 hover:text-white' }}">
        <div
            class="w-11 h-11 rounded-2xl {{ request()->routeIs('user.account.security') ? 'bg-accent-primary/20 text-accent-primary' : 'bg-white/5 text-slate-500' }} flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold leading-tight">{{ __('Security Center') }}</p>
            <p class="text-[10px] opacity-50 font-medium mt-1 uppercase tracking-wider">{{ __('Access & Sessions') }}
            </p>
        </div>
    </a>
</div>
