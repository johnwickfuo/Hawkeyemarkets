<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('site.template') }} Design System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/' . getSetting('favicon', 'favicon.png')) }}">
    {{-- indexing --}}
    @if (config('site.search_engine_indexing'))
        <meta name="robots" content="index, follow">
    @else
        <meta name="robots" content="noindex, nofollow">
    @endif
    @if (config('site.user_vite'))
        @vite(['resources/views/templates/bento/css/app.css', 'resources/views/templates/bento/js/app.js'])
    @else
        <link rel="stylesheet"
            href="{{ asset('assets/templates/bento/css/main.css' . '?v=' . filemtime(public_path('assets/templates/bento/css/main.css'))) }}">
        <script
            src="{{ asset('assets/templates/bento/js/main.js' . '?v=' . filemtime(public_path('assets/templates/bento/js/main.js'))) }}">
        </script>
    @endif
</head>

<body class="bg-primary-dark text-text-primary font-body antialiased min-h-screen p-8 lg:p-12">

    <div class="max-w-7xl mx-auto space-y-20">

        <!-- Header -->
        <header class="flex items-center justify-between border-b border-white/10 pb-10">
            <div>
                <h1 class="font-heading text-4xl font-bold mb-2">Design System <span
                        class="text-accent-primary">.</span></h1>
                <p class="text-text-secondary">Visual style guide and component library for the Bento template.</p>
            </div>
            <div class="flex items-center gap-4">
                <span
                    class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-xs font-mono text-text-secondary">v1.0.0</span>
            </div>
        </header>

        <!-- Typography -->
        <section class="space-y-8">
            <h2 class="text-2xl font-heading font-bold pb-4 border-b border-white/5 text-white/50">01. Typography</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div>
                        <span class="text-xs text-accent-primary font-mono uppercase tracking-wider mb-2 block">Display
                            & Headings</span>
                        <p class="font-display text-5xl md:text-6xl font-bold leading-tight">
                            The quick brown fox jumps over the lazy dog.
                        </p>
                        <p class="mt-2 text-sm text-text-secondary font-mono">Font: Space Grotesk / Bold</p>
                    </div>

                    <div>
                        <span class="text-xs text-accent-primary font-mono uppercase tracking-wider mb-2 block">Body
                            Text</span>
                        <p class="font-body text-lg text-text-secondary leading-relaxed">
                            Lozand provides a premium investment experience through a curated selection of high-yield
                            opportunities. Our platform leverages cutting-edge technology to ensure security,
                            transparency, and optimal performance for every portfolio.
                        </p>
                        <p class="mt-2 text-sm text-text-secondary font-mono">Font: Inter / Regular</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="p-6 rounded-xl bg-secondary-dark border border-white/5">
                        <div class="flex justify-between items-end mb-4">
                            <h3 class="font-heading text-2xl font-bold">Type Scale</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-baseline gap-4">
                                <span class="w-16 text-xs text-text-secondary font-mono">6xl</span>
                                <span class="text-6xl font-heading font-bold">Aa</span>
                            </div>
                            <div class="flex items-baseline gap-4">
                                <span class="w-16 text-xs text-text-secondary font-mono">4xl</span>
                                <span class="text-4xl font-heading font-bold">Aa</span>
                            </div>
                            <div class="flex items-baseline gap-4">
                                <span class="w-16 text-xs text-text-secondary font-mono">2xl</span>
                                <span class="text-2xl font-heading font-bold">Aa</span>
                            </div>
                            <div class="flex items-baseline gap-4">
                                <span class="w-16 text-xs text-text-secondary font-mono">xl</span>
                                <span class="text-xl font-heading font-bold">Aa</span>
                            </div>
                            <div class="flex items-baseline gap-4">
                                <span class="w-16 text-xs text-text-secondary font-mono">base</span>
                                <span class="text-base font-body">Aa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Color Palette -->
        <section class="space-y-8">
            <h2 class="text-2xl font-heading font-bold pb-4 border-b border-white/5 text-white/50">02. Color Palette
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Primary Dark -->
                <div class="space-y-3 group">
                    <div
                        class="h-32 rounded-xl bg-primary-dark border border-white/10 shadow-lg group-hover:scale-105 transition-transform">
                    </div>
                    <div>
                        <p class="font-bold text-white">Primary Dark</p>
                        <p class="text-xs text-text-secondary font-mono">#0f172a</p>
                        <p class="text-[10px] text-text-secondary/50 uppercase tracking-widest mt-1">Background</p>
                    </div>
                </div>

                <!-- Secondary Dark -->
                <div class="space-y-3 group">
                    <div
                        class="h-32 rounded-xl bg-secondary-dark border border-white/10 shadow-lg group-hover:scale-105 transition-transform">
                    </div>
                    <div>
                        <p class="font-bold text-white">Secondary Dark</p>
                        <p class="text-xs text-text-secondary font-mono">#1e293b</p>
                        <p class="text-[10px] text-text-secondary/50 uppercase tracking-widest mt-1">Surface</p>
                    </div>
                </div>

                <!-- Accent Primary -->
                <div class="space-y-3 group">
                    <div
                        class="h-32 rounded-xl bg-accent-primary shadow-[0_8px_30px_rgb(139,92,246,0.3)] group-hover:scale-105 transition-transform">
                    </div>
                    <div>
                        <p class="font-bold text-white">Accent Primary</p>
                        <p class="text-xs text-text-secondary font-mono">#8b5cf6</p>
                        <p class="text-[10px] text-text-secondary/50 uppercase tracking-widest mt-1">Brand</p>
                    </div>
                </div>

                <!-- Accent Secondary -->
                <div class="space-y-3 group">
                    <div
                        class="h-32 rounded-xl bg-accent-secondary shadow-[0_8px_30px_rgb(6,182,212,0.3)] group-hover:scale-105 transition-transform">
                    </div>
                    <div>
                        <p class="font-bold text-white">Accent Cyan</p>
                        <p class="text-xs text-text-secondary font-mono">#06b6d4</p>
                        <p class="text-[10px] text-text-secondary/50 uppercase tracking-widest mt-1">Secondary</p>
                    </div>
                </div>

                <!-- Text Primary -->
                <div class="space-y-3 group">
                    <div
                        class="h-32 rounded-xl bg-text-primary border border-white/10 group-hover:scale-105 transition-transform">
                    </div>
                    <div>
                        <p class="font-bold text-white">Text Primary</p>
                        <p class="text-xs text-text-secondary font-mono">#f8fafc</p>
                        <p class="text-[10px] text-text-secondary/50 uppercase tracking-widest mt-1">Headings</p>
                    </div>
                </div>

                <!-- Text Secondary -->
                <div class="space-y-3 group">
                    <div class="h-32 rounded-xl bg-text-secondary group-hover:scale-105 transition-transform"></div>
                    <div>
                        <p class="font-bold text-white">Text Secondary</p>
                        <p class="text-xs text-text-secondary font-mono">#94a3b8</p>
                        <p class="text-[10px] text-text-secondary/50 uppercase tracking-widest mt-1">Body</p>
                    </div>
                </div>
            </div>

            <!-- Semantic Colors -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-8">
                <div class="flex items-center gap-4 p-4 rounded-xl bg-success/10 border border-success/20">
                    <div class="w-10 h-10 rounded-full bg-success shadow-[0_0_15px_rgba(16,185,129,0.4)]"></div>
                    <div>
                        <p class="text-sm font-bold text-success">Success</p>
                        <p class="text-xs text-text-secondary font-mono">#10b981</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-xl bg-warning/10 border border-warning/20">
                    <div class="w-10 h-10 rounded-full bg-warning shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                    <div>
                        <p class="text-sm font-bold text-warning">Warning</p>
                        <p class="text-xs text-text-secondary font-mono">#f59e0b</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-xl bg-error/10 border border-error/20">
                    <div class="w-10 h-10 rounded-full bg-error shadow-[0_0_15px_rgba(239,68,68,0.4)]"></div>
                    <div>
                        <p class="text-sm font-bold text-error">Error</p>
                        <p class="text-xs text-text-secondary font-mono">#ef4444</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-4 rounded-xl bg-info/10 border border-info/20">
                    <div class="w-10 h-10 rounded-full bg-info shadow-[0_0_15px_rgba(59,130,246,0.4)]"></div>
                    <div>
                        <p class="text-sm font-bold text-info">Info</p>
                        <p class="text-xs text-text-secondary font-mono">#3b82f6</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Components -->
        <section class="space-y-8">
            <h2 class="text-2xl font-heading font-bold pb-4 border-b border-white/5 text-white/50">03. UI Components
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Buttons -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-white">Buttons</h3>
                    <div
                        class="flex flex-wrap gap-4 items-center p-8 rounded-2xl bg-secondary-dark border border-white/5">
                        <button
                            class="px-6 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-glow transition-colors shadow-lg shadow-accent-primary/20">
                            Primary Action
                        </button>
                        <button
                            class="px-6 py-3 rounded-xl bg-white/5 text-text-secondary font-medium border border-white/10 hover:text-white hover:bg-white/10 transition-colors">
                            Secondary
                        </button>
                        <button
                            class="px-6 py-3 rounded-xl text-accent-primary font-bold hover:bg-accent-primary/10 transition-colors">
                            Tertiary
                        </button>
                    </div>
                </div>

                <!-- Inputs -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-white">Form Elements</h3>
                    <div class="space-y-4 p-8 rounded-2xl bg-secondary-dark border border-white/5">
                        <div>
                            <label
                                class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">Email
                                Address</label>
                            <input type="email" placeholder="jack@example.com"
                                class="w-full px-4 py-3 rounded-xl bg-primary-dark border border-white/10 text-white placeholder-text-secondary/50 focus:outline-none focus:border-accent-primary/50 focus:ring-1 focus:ring-accent-primary/50 transition-all">
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" checked
                                class="w-5 h-5 rounded border-gray-600 bg-primary-dark text-accent-primary focus:ring-offset-0">
                            <span class="text-sm text-text-secondary">Remember me on this device</span>
                        </div>
                    </div>
                </div>

                <!-- Cards -->
                <div class="space-y-6 lg:col-span-2">
                    <h3 class="text-lg font-bold text-white">Bento Cards</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Card 1 -->
                        <div
                            class="p-6 rounded-2xl bg-secondary-dark border border-white/5 hover:border-accent-primary/30 transition-all group relative overflow-hidden">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="w-12 h-12 rounded-lg bg-accent-primary/10 flex items-center justify-center mb-4 text-accent-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2v20" />
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-white mb-2">Investment Growth</h4>
                                <p class="text-text-secondary text-sm">Track your portfolio performance with real-time
                                    analytics and detailed reporting.</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="p-6 rounded-2xl bg-secondary-dark border border-white/5 hover:border-accent-secondary/30 transition-all group relative overflow-hidden">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-accent-secondary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="w-12 h-12 rounded-lg bg-accent-secondary/10 flex items-center justify-center mb-4 text-accent-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-white mb-2">Security First</h4>
                                <p class="text-text-secondary text-sm">Enterprise-grade security protocols ensuring
                                    your assets remain safe at all times.</p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div
                            class="p-6 rounded-2xl bg-secondary-dark border border-white/5 hover:border-white/20 transition-all group relative overflow-hidden">
                            <div
                                class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors">
                            </div>
                            <div class="relative z-10">
                                <div
                                    class="w-12 h-12 rounded-lg bg-white/5 flex items-center justify-center mb-4 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                        <path d="M12 17h.01" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-bold text-white mb-2">24/7 Support</h4>
                                <p class="text-text-secondary text-sm">Our dedicated support team is available around
                                    the clock to assist you.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

</body>

</html>
