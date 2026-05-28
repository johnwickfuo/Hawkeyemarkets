<div id="cookie-consent-banner"
    class="fixed bottom-6 left-6 right-6 lg:left-auto lg:right-8 lg:max-w-md z-[100] transform translate-y-24 opacity-0 transition-all duration-700 ease-out hidden">
    <div
        class="relative p-6 rounded-[2rem] bg-[#0B0F17]/80 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden group">
        {{-- Atmospheric Glow --}}
        <div
            class="absolute -top-12 -right-12 w-32 h-32 bg-accent-primary/10 rounded-full blur-2xl group-hover:bg-accent-primary/20 transition-all duration-500">
        </div>

        <div class="relative z-10">
            <div class="flex items-center gap-4 mb-4">
                <div
                    class="w-10 h-10 rounded-xl bg-accent-primary/20 flex items-center justify-center text-accent-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-white leading-tight uppercase tracking-tight">
                    {{ __('Cookie') }} <span class="text-accent-primary">{{ __('Protocol') }}</span>
                </h3>
            </div>

            <p class="text-sm text-text-secondary leading-relaxed mb-6">
                {{ __('We utilize operational and analytical protocols (cookies) to ensure a secure, institutional-grade investment experience. By continuing, you agree to our structural data boundaries.') }}
            </p>

            <div class="flex flex-col sm:flex-row gap-3">
                <button id="accept-cookies"
                    class="flex-1 px-6 py-3 bg-accent-primary text-white rounded-xl font-bold text-sm hover:bg-accent-primary/90 transition-all shadow-lg shadow-accent-primary/20 hover:-translate-y-0.5 active:translate-y-0">
                    {{ __('Accept All') }}
                </button>
                <a href="{{ route('privacy-policy') }}"
                    class="flex-1 px-6 py-3 bg-white/5 border border-white/10 text-white rounded-xl font-bold text-sm text-center hover:bg-white/10 transition-all">
                    {{ __('Privacy Policy') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('cookie-consent-banner');
        const acceptBtn = document.getElementById('accept-cookies');

        // Check if consent has already been given
        if (!localStorage.getItem('cookie-consent-given')) {
            // Show banner with delay for cinematic feel
            setTimeout(() => {
                banner.classList.remove('hidden');
                // Force reflow
                void banner.offsetWidth;
                banner.classList.remove('translate-y-24', 'opacity-0');
            }, 2000);
        }

        acceptBtn.addEventListener('click', function() {
            // Hide banner
            banner.classList.add('translate-y-24', 'opacity-0');

            // Save preference
            localStorage.setItem('cookie-consent-given', 'true');

            // Remove from DOM after transition
            setTimeout(() => {
                banner.classList.add('hidden');
            }, 700);
        });
    });
</script>
