<div class="flex flex-col gap-2">
    <a href="{{ route('admin.account.profile') }}"
        class="flex items-center gap-3 px-6 py-4 rounded-2xl border transition-all duration-300 {{ request()->routeIs('admin.account.profile') ? 'bg-accent-primary/10 border-accent-primary/20 text-white' : 'bg-white/3 border-white/5 text-slate-400 hover:bg-white/5 hover:text-white' }}">
        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.account.profile') ? 'text-accent-primary' : 'text-slate-500' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold leading-tight">{{ __('Profile Details') }}</p>
            <p class="text-[10px] opacity-50 font-medium">{{ __('Personal information') }}</p>
        </div>
    </a>

    <a href="{{ route('admin.account.security') }}"
        class="flex items-center gap-3 px-6 py-4 rounded-2xl border transition-all duration-300 {{ request()->routeIs('admin.account.security') ? 'bg-accent-primary/10 border-accent-primary/20 text-white' : 'bg-white/3 border-white/5 text-slate-400 hover:bg-white/5 hover:text-white' }}">
        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
            <svg class="w-5 h-5 {{ request()->routeIs('admin.account.security') ? 'text-accent-primary' : 'text-slate-500' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold leading-tight">{{ __('Security') }}</p>
            <p class="text-[10px] opacity-50 font-medium">{{ __('Passwords & Sessions') }}</p>
        </div>
    </a>
</div>
