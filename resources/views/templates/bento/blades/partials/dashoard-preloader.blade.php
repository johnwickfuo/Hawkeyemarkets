@if (getSetting('preloader', 'enabled') === 'enabled')
    <div id="dashboard-preloader"
        class="fixed inset-0 z-[99999] bg-[#0a0a0c] flex items-center justify-center overflow-hidden transition-all duration-700">
        <!-- Background Gradient Orbs -->
        <div
            class="absolute top-[-10%] left-[-10%] w-[50vh] h-[50vh] bg-accent-primary/10 rounded-full blur-[120px] animate-blob">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[50vh] h-[50vh] bg-purple-500/10 rounded-full blur-[120px] animate-blob animation-delay-2000">
        </div>

        <div class="relative flex flex-col items-center">
            <!-- Loader Animation -->
            <div class="relative w-32 h-32 mb-12">
                {{-- Outer Ring --}}
                <div class="absolute inset-0 border-[3px] border-white/5 rounded-full"></div>
                <div
                    class="absolute inset-0 border-[3px] border-transparent border-t-accent-primary border-r-accent-primary/50 rounded-full animate-spin-slow shadow-[0_0_30px_rgba(var(--color-accent-primary),0.2)]">
                </div>

                {{-- Inner Ring --}}
                <div class="absolute inset-6 border-[3px] border-white/5 rounded-full"></div>
                <div
                    class="absolute inset-6 border-[3px] border-transparent border-b-purple-500 border-l-purple-500/50 rounded-full animate-spin-reverse shadow-[0_0_30px_rgba(168,85,247,0.2)]">
                </div>

                {{-- Center Core --}}
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-2 h-2 bg-white rounded-full animate-ping"></div>
                </div>
            </div>

            <!-- Text & Progress -->
            <div class="flex flex-col items-center space-y-4 z-10">
                <h2 class="text-2xl font-bold text-white tracking-[0.2em] uppercase animate-pulse">
                    {{ getSetting('name') }}
                </h2>

                <div class="flex items-center gap-3">
                    <div class="h-[2px] w-12 bg-gradient-to-r from-transparent to-accent-primary animate-pulse"></div>
                    <p class="text-text-secondary text-[10px] uppercase tracking-[0.3em] font-medium">
                        {{ __('System Initializing') }}
                    </p>
                    <div class="h-[2px] w-12 bg-gradient-to-l from-transparent to-accent-primary animate-pulse"></div>
                </div>
            </div>
        </div>
    </div>


    {{-- floating buy/purchase button  --}}


    <style>
        @keyframes spin-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes spin-reverse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        .animate-spin-reverse {
            animation: spin-reverse 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 10s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const preloader = document.getElementById('dashboard-preloader');

            // Initial state check - if we just navigated here, it should be visible by default HTML

            // Helper to hide
            function hidePreloader() {
                if (preloader && preloader.style.display !== 'none') {
                    preloader.style.opacity = '0';
                    preloader.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 700);
                }
            }

            // Helper to show
            function showPreloader() {
                if (preloader) {
                    preloader.style.display = 'flex';
                    // Force reflow
                    void preloader.offsetWidth;

                    preloader.style.opacity = '1';
                    preloader.style.transform = 'scale(1)';

                    // Safety timeout
                    setTimeout(() => {
                        // If we are still here after 10s, something is wrong or user cancelled
                        hidePreloader();
                    }, 10000);
                }
            }

            // Initial Page Load
            window.addEventListener('load', () => {
                // Delay slightly to show off animation
                setTimeout(() => {
                    hidePreloader();
                }, 800);
            });

            // Fallback safety
            setTimeout(() => {
                hidePreloader();
            }, 5000);

            // Handle Anchor Clicks
            document.addEventListener('click', function(e) {
                const anchor = e.target.closest('a');

                if (!anchor || !anchor.href) return;

                // Ignore special protocols
                if (anchor.protocol === 'javascript:' || anchor.protocol === 'mailto:' || anchor
                    .protocol === 'tel:') return;

                // Ignore target blank
                if (anchor.target === '_blank') return;

                // Ignore modifier keys
                if (e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) return;

                // Check for download attribute
                if (anchor.hasAttribute('download')) return;

                // Ignore PDF Preview Links
                if (anchor.classList.contains('pdf-preview-link')) return;

                // Ignore AJAX Pagination
                if (anchor.closest('.ajax-pagination')) return;

                // Check if same page hash navigation
                const isSamePage = (anchor.pathname === window.location.pathname) &&
                    (anchor.search === window.location.search);

                if (isSamePage && anchor.hash) return; // Animated scroll or hash change

                // if the url contains # return
                if (anchor.href.includes('#')) return;

                // Show preloader
                showPreloader();
            });

            // Handle Back/Forward Cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    hidePreloader();
                }
            });
        });
    </script>

    {{-- Floating Buy/Purchase Button --}}
    @if (config('app.env') === 'sandbox')
        <a href="https://lozand.com" target="_blank"
            class="fixed top-2/3 md:top-1/2 right-0 md:right-8 -translate-y-1/2 z-[9999] group flex items-center justify-center p-0.5 rounded-2xl bg-gradient-to-br from-accent-primary via-purple-500 to-accent-secondary shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_30px_rgba(var(--color-accent-primary),0.5)] transition-all duration-500 hover:scale-105 active:scale-95 animate-float-attention">
            <div
                class="relative flex items-center gap-3 px-6 py-3 rounded-[14px] bg-[#0a0a0c]/80 backdrop-blur-xl transition-colors group-hover:bg-[#0a0a0c]/40 overflow-hidden">
                {{-- Animated Background Glow --}}
                <div
                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-shimmer">
                </div>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
                <span
                    class="text-sm font-bold text-white tracking-wide uppercase hidden md:flex">{{ __('Purchase Now') }}</span>

                {{-- Pulse Rings --}}
                <div
                    class="absolute inset-0 rounded-[14px] ring-1 ring-white/20 group-hover:ring-white/40 transition-all">
                </div>
            </div>
        </a>
    @endif

    <style>
        @keyframes float-attention {

            0%,
            100% {
                transform: translateY(-50%) translateX(0);
            }

            25% {
                transform: translateY(calc(-50% - 8px)) translateX(2px);
            }

            50% {
                transform: translateY(-50%) translateX(-2px);
            }

            75% {
                transform: translateY(calc(-50% + 4px)) translateX(1px);
            }
        }

        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .animate-float-attention {
            animation: float-attention 4s ease-in-out infinite;
        }

        .group-hover\:animate-shimmer:hover .animate-shimmer,
        .group:hover .group-hover\:animate-shimmer {
            animation: shimmer 1.5s infinite;
        }

        .group:hover [class*="group-hover:animate-shimmer"] {
            animation: shimmer 1.5s infinite;
        }
    </style>
@endif
