@php
    $settings = [
        [
            'title' => 'Overview',
            'route' => 'admin.settings.index',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>',
        ],
        [
            'title' => 'Legal',
            'route' => 'admin.settings.legal',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
        ],
        [
            'title' => 'Activation',
            'route' => 'admin.settings.activation',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
        ],
        [
            'title' => 'System',
            'route' => 'admin.settings.system',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        ],
        [
            'title' => 'Audit',
            'route' => 'admin.settings.audit',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
        ],

        [
            'title' => 'Core Settings',
            'route' => 'admin.settings.core',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>',
        ],
        [
            'title' => 'Email',
            'route' => 'admin.settings.email',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>',
        ],
        [
            'title' => 'CronJob',
            'route' => 'admin.settings.cronjob',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        [
            'title' => 'Financial',
            'route' => 'admin.settings.financial',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        [
            'title' => 'Deposit',
            'route' => 'admin.settings.deposit.index',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
        ],
        [
            'title' => 'Withdrawal',
            'route' => 'admin.settings.withdrawal.index',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
        ],

        [
            'title' => 'Security',
            'route' => 'admin.settings.security',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
        ],

        [
            'title' => 'Login Methods',
            'route' => 'admin.settings.login-method',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>',
        ],

        [
            'title' => 'Bonus System',
            'route' => 'admin.settings.bonus-system',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
        ],

        [
            'title' => 'Certificates',
            'route' => 'admin.settings.certificate',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>',
        ],

        [
            'title' => 'SEO',
            'route' => 'admin.settings.seo',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>',
        ],

        [
            'title' => 'Menu',
            'route' => 'admin.settings.menu',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>',
        ],
        [
            'title' => 'Utilities',
            'route' => 'admin.settings.utility',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
        ],
        [
            'title' => 'Livechat & Scripts',
            'route' => 'admin.settings.livechat',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>',
        ],
        [
            'title' => 'Modules',
            'route' => 'admin.settings.modules.index',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" /></svg>',
        ],
        [
            'title' => 'Management Team',
            'route' => 'admin.settings.management-team.index',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
        ],
        [
            'title' => 'Client Reviews',
            'route' => 'admin.settings.reviews.index',
            'icon' =>
                '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>',
        ],
    ];
@endphp

<div class="space-y-1" id="sideBarSelector">
    @foreach ($settings as $item)
        @php
            $url = Route::has($item['route']) ? route($item['route']) : '#';
            $isActive = request()->routeIs($item['route']);
        @endphp
        <a href="{{ $url }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ $isActive ? 'bg-accent-primary/10 text-accent-primary' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <div
                class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors {{ $isActive ? 'bg-accent-primary/20 text-accent-primary' : 'bg-white/5 text-slate-500 group-hover:bg-white/10 group-hover:text-white' }}">
                {!! $item['icon'] !!}
            </div>
            <span class="text-sm font-bold tracking-tight">
                {{ __($item['title']) }}
            </span>
            @if ($isActive)
                <div
                    class="ml-auto w-1.5 h-1.5 rounded-full bg-accent-primary shadow-[0_0_8px_rgba(var(--accent-primary-rgb),0.6)]">
                </div>
            @endif
        </a>
    @endforeach
</div>

{{-- scroll mobile div --}}
<div id="scrollToMobile"></div>


@push('css')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(var(--accent-primary-rgb), 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(var(--accent-primary-rgb), 0.3);
        }
    </style>
@endpush



@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sideBarSelector = document.getElementById('sideBarSelector');
            const floatingBtn = document.getElementById('floatingBackToSidebar');
            const scrollToMobile = document.getElementById('scrollToMobile');

            if (!floatingBtn) return;

            // 1) Move button to body so "fixed" is truly fixed
            if (floatingBtn.parentElement !== document.body) {
                document.body.appendChild(floatingBtn);
            }

            // 2) Show/hide logic
            const isMobile = () => window.innerWidth < 1024;

            const toggleBtn = () => {
                if (!isMobile()) {
                    floatingBtn.classList.add('hidden');
                    return;
                }

                // If page is short (no real scroll), you can choose to always show it:
                // comment this block out if you ONLY want it after scrolling.
                const pageScrollable = document.documentElement.scrollHeight > (window.innerHeight + 50);
                if (!pageScrollable) {
                    floatingBtn.classList.remove('hidden');
                    return;
                }

                // Normal behavior: show after some scroll
                if (window.scrollY > 120) floatingBtn.classList.remove('hidden');
                else floatingBtn.classList.add('hidden');
            };

            // Initial state
            toggleBtn();

            window.addEventListener('scroll', toggleBtn, {
                passive: true
            });
            window.addEventListener('resize', toggleBtn, {
                passive: true
            });

            // 3) Your initial scroll-to-mobile (this changes scroll position)
            if (isMobile() && scrollToMobile) {
                // Do the scroll, then re-evaluate button visibility after it completes
                scrollToMobile.scrollIntoView({
                    behavior: 'smooth'
                });

                // Re-check after a short delay so scrollY updates
                setTimeout(toggleBtn, 400);
                setTimeout(toggleBtn, 900);
            }

            // 4) Click action
            floatingBtn.addEventListener('click', () => {
                if (!sideBarSelector) return;
                sideBarSelector.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    </script>
@endpush

{{-- Floating Mobile Button - Persistent with Caret Up --}}
<button id="floatingBackToSidebar"
    class="fixed bottom-8 right-8 z-[9999] lg:hidden hidden w-14 h-14 rounded-2xl bg-accent-primary text-white shadow-2xl shadow-accent-primary/40 flex items-center justify-center active:scale-95 border border-white/20 backdrop-blur-md">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" />
    </svg>
</button>
