@extends('templates.' . config('site.template') . '.blades.layouts.front')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative pt-32 pb-24 overflow-hidden bg-[#050505]">
        {{-- Atmospheric Background --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(168,85,247,0.08),transparent_70%)]">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:60px_60px]">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 mb-8 animate-fade-in-up">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    <span
                        class="text-[10px] font-black text-purple-500 uppercase tracking-widest">{{ __('Regulatory Protocol') }}</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-8 leading-tight tracking-tighter">
                    {{ __('Terms &') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-600">{{ __('Conditions.') }}</span>
                </h1>
                <p class="text-xl text-text-secondary leading-relaxed mb-6 max-w-2xl mx-auto">
                    {{ __('Comprehensive governance for the :site_name investment ecosystem. By accessing our platform, you acknowledge and agree to the structured operational standards detailed below.', ['site_name' => getSetting('name')]) }}
                </p>
                <div
                    class="flex items-center justify-center gap-4 text-[10px] font-black uppercase tracking-[0.2em] text-white/40">
                    <span>{{ __('Effective Date') }}: {{ date('F d, Y') }}</span>
                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                    <span>{{ __('Version') }}: Institutional 3.1</span>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="pb-32 relative bg-[#050505]">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-16">
                {{-- LEFT SIDEBAR: STICKY NAVIGATION --}}
                <aside class="lg:w-1/4">
                    <div class="sticky top-32 space-y-8">
                        <div>
                            <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mb-6">
                                {{ __('Table of Protocols') }}
                            </h3>
                            <nav class="flex flex-col gap-2">
                                <a href="#user-eligibility"
                                    class="group flex items-center gap-3 py-3 px-5 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/5 transition-all text-sm font-bold text-text-secondary hover:text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    {{ __('1. User Eligibility') }}
                                </a>
                                <a href="#capital-instruments"
                                    class="group flex items-center gap-3 py-3 px-5 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/5 transition-all text-sm font-bold text-text-secondary hover:text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    {{ __('2. Capital Instruments') }}
                                </a>
                                <a href="#managed-portfolios"
                                    class="group flex items-center gap-3 py-3 px-5 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/5 transition-all text-sm font-bold text-text-secondary hover:text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    {{ __('3. Managed Portfolios') }}
                                </a>
                                <a href="#self-trading"
                                    class="group flex items-center gap-3 py-3 px-5 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/5 transition-all text-sm font-bold text-text-secondary hover:text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    {{ __('4. Self Trading Protocols') }}
                                </a>
                                <a href="#sector-activity"
                                    class="group flex items-center gap-3 py-3 px-5 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/5 transition-all text-sm font-bold text-text-secondary hover:text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    {{ __('5. Sector-Specific Terms') }}
                                </a>
                                <a href="#prohibited-conduct"
                                    class="group flex items-center gap-3 py-3 px-5 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/5 transition-all text-sm font-bold text-text-secondary hover:text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    {{ __('6. Prohibited Conduct') }}
                                </a>
                                <a href="#liability"
                                    class="group flex items-center gap-3 py-3 px-5 rounded-2xl bg-white/5 border border-white/5 hover:border-purple-500/30 hover:bg-purple-500/5 transition-all text-sm font-bold text-text-secondary hover:text-white">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    {{ __('7. Liability & Indemnity') }}
                                </a>
                            </nav>
                        </div>

                        {{-- Supportive Info Panel --}}
                        <div class="p-8 rounded-3xl bg-purple-500/5 border border-purple-500/10">
                            <h4 class="text-white font-black mb-4 uppercase text-xs tracking-widest">
                                {{ __('Legal Clarity') }}</h4>
                            <p class="text-[11px] text-text-secondary leading-relaxed mb-6">
                                {{ __('These terms are designed to protect both the firm and the individual investor from market-wide anomalies and bad actors.') }}
                            </p>
                            <a href="{{ route('contact') }}"
                                class="text-[10px] font-black uppercase tracking-widest text-purple-500 hover:text-purple-400">
                                {{ __('Consult Compliance') }} &rarr;
                            </a>
                        </div>
                    </div>
                </aside>

                {{-- RIGHT CONTENT: DETAILED SECTIONS --}}
                <div class="lg:w-3/4">
                    <div class="space-y-32">
                        {{-- 1. USER ELIGIBILITY --}}
                        <section id="user-eligibility" class="scroll-mt-32">
                            <h2 class="text-4xl font-black text-white mb-8 flex items-center gap-6">
                                <span
                                    class="w-8 h-8 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-500 text-sm">01</span>
                                {{ __('User Eligibility & Security') }}
                            </h2>
                            <div class="space-y-8 text-text-secondary leading-relaxed text-lg">
                                <p>{{ __('To utilize the :site_name ecosystem, users must be at least 18 years of age and possess the legal capacity to enter into binding financial agreements.', ['site_name' => getSetting('name')]) }}
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10">
                                        <h4 class="text-white font-bold mb-2">{{ __('Identity Verification') }}</h4>
                                        <p class="text-sm">
                                            {{ __('Completion of tiered KYC/AML protocols is mandatory for all deposit and withdrawal activities.') }}
                                        </p>
                                    </div>
                                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10">
                                        <h4 class="text-white font-bold mb-2">{{ __('Account Integrity') }}</h4>
                                        <p class="text-sm">
                                            {{ __('Users are solely responsible for maintaining 2FA and secure access points to prevent unauthorized capital outflow.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- 2. CAPITAL INSTRUMENTS --}}
                        <section id="capital-instruments" class="scroll-mt-32">
                            <h2 class="text-4xl font-black text-white mb-8 flex items-center gap-6">
                                <span
                                    class="w-8 h-8 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-500 text-sm">02</span>
                                {{ __('Capital Instruments') }}
                            </h2>
                            <div class="space-y-8 text-text-secondary leading-relaxed text-lg">
                                <p>{{ __('Execution of trades for Stocks, Bonds, Mutual Funds, and ETFs is subject to market availability and institutional liquidity buffers.') }}
                                </p>

                                <div class="space-y-6">
                                    <div
                                        class="p-8 rounded-[2rem] bg-white/5 border border-white/10 hover:border-purple-500/20 transition-all">
                                        <h3 class="text-xl font-black text-white mb-4 uppercase tracking-tight">
                                            {{ __('Institutional Bonds') }}</h3>
                                        <p class="text-base mb-4">
                                            {{ __('Fixed income instruments are held in custody by regulated third-party entities. Yields are subject to issuer creditworthiness and market fluctuations.') }}
                                        </p>
                                        <ul class="list-disc pl-6 text-sm space-y-2 text-white/40">
                                            <li>{{ __('Maturity dates are fixed and early liquidation may incur substantial penalties.') }}
                                            </li>
                                            <li>{{ __('Coupon payments are processed based on the underlying asset distribution cycle.') }}
                                            </li>
                                        </ul>
                                    </div>

                                    <div
                                        class="p-8 rounded-[2rem] bg-white/5 border border-white/10 hover:border-purple-500/20 transition-all">
                                        <h3 class="text-xl font-black text-white mb-4 uppercase tracking-tight">
                                            {{ __('Equity & Mutual Funds') }}</h3>
                                        <p class="text-base mb-4">
                                            {{ __('Corporate actions, dividends, and stock splits will be applied to user accounts in accordance with standard market settlement cycles.') }}
                                        </p>
                                        <ul class="list-disc pl-6 text-sm space-y-2 text-white/40">
                                            <li>{{ __('Expense ratios for Mutual Funds are deducted as per the fund manager protocols.') }}
                                            </li>
                                            <li>{{ __('NAV (Net Asset Value) calculation is performed daily at market close.') }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- 3. MANAGED PORTFOLIOS --}}
                        <section id="managed-portfolios" class="scroll-mt-32">
                            <h2 class="text-4xl font-black text-white mb-8 flex items-center gap-6">
                                <span
                                    class="w-8 h-8 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-500 text-sm">03</span>
                                {{ __('Managed Portfolios') }}
                            </h2>
                            <div class="space-y-8 text-text-secondary leading-relaxed text-lg">
                                <p>{{ __('Our Investment Plans utilize algorithmic modeling and institutional rebalancing to optimize risk-adjusted returns.') }}
                                </p>

                                <div class="border-l-4 border-purple-500/40 pl-8 space-y-6 italic text-white/60 text-base">
                                    <p>{{ __('Allocations within Investment Plans are subject to change based on internal risk parameter shifts without individual user notification.') }}
                                    </p>
                                    <p>{{ __('Profit targets described in plan tiers are projections based on historical data and do not constitute an absolute guarantee of yield.') }}
                                    </p>
                                </div>
                            </div>
                        </section>

                        {{-- 4. SELF TRADING PROTOCOLS --}}
                        <section id="self-trading" class="scroll-mt-32">
                            <h2 class="text-4xl font-black text-white mb-8 flex items-center gap-6">
                                <span
                                    class="w-8 h-8 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-500 text-sm">04</span>
                                {{ __('Self Trading Protocols') }}
                            </h2>
                            <div class="space-y-8 text-text-secondary leading-relaxed text-lg">
                                <p>{{ __('Trading across Futures, Margin, Forex, and Commodities requires a comprehensive understanding of leverage and liquidation thresholds.') }}
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-4">
                                        <h4 class="text-white font-black uppercase text-xs tracking-widest">
                                            {{ __('Leverage & Margin') }}</h4>
                                        <p class="text-sm">
                                            {{ __('Margin requirements are dynamically adjusted based on market volatility. Positions will be automatically liquidated if the maintenance margin threshold is crossed.') }}
                                        </p>
                                    </div>
                                    <div class="space-y-4">
                                        <h4 class="text-white font-black uppercase text-xs tracking-widest">
                                            {{ __('Futures Expiry') }}</h4>
                                        <p class="text-sm">
                                            {{ __('Users are responsible for monitoring contract expiry dates. Open positions at expiry will be settled as per the underlying exchange settlement price.') }}
                                        </p>
                                    </div>
                                    <div class="space-y-4">
                                        <h4 class="text-white font-black uppercase text-xs tracking-widest">
                                            {{ __('Forex Execution') }}</h4>
                                        <p class="text-sm">
                                            {{ __('Forex pairs are subject to spreads which vary according to liquidity and market timeframes (Asian, European, US sessions).') }}
                                        </p>
                                    </div>
                                    <div class="space-y-4">
                                        <h4 class="text-white font-black uppercase text-xs tracking-widest">
                                            {{ __('Commodity Physicals') }}</h4>
                                        <p class="text-sm">
                                            {{ __('Trading in commodities on :site_name is strictly for financial speculation; we do not provide physical delivery of underlying assets.', ['site_name' => getSetting('name')]) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- 5. SECTOR-SPECIFIC TERMS --}}
                        <section id="sector-activity" class="scroll-mt-32">
                            <h2 class="text-4xl font-black text-white mb-8 flex items-center gap-6">
                                <span
                                    class="w-8 h-8 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-500 text-sm">05</span>
                                {{ __('Sector-Specific Terms') }}
                            </h2>
                            <div class="space-y-8 text-text-secondary leading-relaxed text-lg">
                                <p>{{ __('Specialized investment sectors incur unique operational rules and custody standards.') }}
                                </p>

                                <div class="prose prose-invert prose-purple max-w-none text-base space-y-12">
                                    <div class="space-y-4">
                                        <h4 class="text-white font-bold">{{ __('Crypto Assets & Digital Liquidity') }}
                                        </h4>
                                        <p>{{ __('Digital assets are stored in multi-sig cold storage where possible. Transaction finality on the blockchain is immutable. :site_name is not liable for errors in destination wallet addresses provided by the user.', ['site_name' => getSetting('name')]) }}
                                        </p>
                                    </div>

                                    <div class="space-y-4">
                                        <h4 class="text-white font-bold">{{ __('Real Estate & Fixed Income') }}</h4>
                                        <p>{{ __('Investments in REITs or Fixed Income vehicles are governed by specific vesting periods. Withdrawal of capital from these sectors may require prior clearing agent approval and up to 14 business days to settle.') }}
                                        </p>
                                    </div>

                                    <div class="space-y-4">
                                        <h4 class="text-white font-bold">
                                            {{ __('Alternative Assets (Art, Gaming, Startups)') }}</h4>
                                        <p>{{ __('Valuation for alternative sectors is performed periodically by third-party appraisal protocols. These assets represent high-risk, high-reward profiles and should only constitute a fraction of a balanced institutional portfolio.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        {{-- 6. PROHIBITED CONDUCT --}}
                        <section id="prohibited-conduct" class="scroll-mt-32">
                            <h2 class="text-4xl font-black text-white mb-8 flex items-center gap-6">
                                <span
                                    class="w-8 h-8 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-500 text-sm">06</span>
                                {{ __('Prohibited Conduct') }}
                            </h2>
                            <div class="space-y-8 text-text-secondary leading-relaxed text-lg">
                                <p>{{ __('Any activity that undermines the integrity of the :site_name ecosystem is strictly prohibited.', ['site_name' => getSetting('name')]) }}
                                </p>

                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 list-none pl-0">
                                    <li
                                        class="flex items-start gap-4 p-4 rounded-2xl bg-red-500/5 border border-red-500/10 text-sm">
                                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        {{ __('Market manipulation, including wash trading and spoofing.') }}
                                    </li>
                                    <li
                                        class="flex items-start gap-4 p-4 rounded-2xl bg-red-500/5 border border-red-500/10 text-sm">
                                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        {{ __('Exploitation of system latency or computational arbitrage.') }}
                                    </li>
                                    <li
                                        class="flex items-start gap-4 p-4 rounded-2xl bg-red-500/5 border border-red-500/10 text-sm">
                                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        {{ __('Reverse engineering of proprietary trading algorithms.') }}
                                    </li>
                                    <li
                                        class="flex items-start gap-4 p-4 rounded-2xl bg-red-500/5 border border-red-500/10 text-sm">
                                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                        {{ __('Unauthorized account access or credential sharing.') }}
                                    </li>
                                </ul>
                            </div>
                        </section>

                        {{-- 7. LIABILITY & INDEMNITY --}}
                        <section id="liability" class="scroll-mt-32">
                            <h2 class="text-4xl font-black text-white mb-8 flex items-center gap-6">
                                <span
                                    class="w-8 h-8 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-500 text-sm">07</span>
                                {{ __('Liability & Indemnification') }}
                            </h2>
                            <div class="space-y-8 text-text-secondary leading-relaxed text-lg">
                                <p>{{ __('To the maximum extent permitted by law, :site_name and its affiliates shall not be liable for any direct, indirect, or consequential losses resulting from market volatility, platform downtime, or third-party custody failures.', ['site_name' => getSetting('name')]) }}
                                </p>

                                <div class="p-10 rounded-[2.5rem] bg-white/5 border border-white/10 text-center">
                                    <p class="text-white font-black uppercase tracking-widest text-sm mb-4">
                                        {{ __('Arbitration Clause') }}</p>
                                    <p class="text-sm">
                                        {{ __('All disputes arising from these terms shall be settled through binding international arbitration. Recourse to civil courts is waived by the user upon account activation.') }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 relative overflow-hidden bg-black/40 border-t border-white/5">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <div
                class="max-w-4xl mx-auto p-12 lg:p-20 rounded-[3rem] bg-gradient-to-b from-white/[0.05] to-transparent border border-white/5 relative overflow-hidden">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 leading-tight">
                    {{ __('Accept the Protocols &') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-purple-600">{{ __('Deploy Capital.') }}</span>
                </h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('user.register') }}"
                        class="px-10 py-5 bg-purple-500 text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-purple-600 transition-all shadow-lg shadow-purple-500/20 hover:-translate-y-1">
                        {{ __('Open Account') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-10 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-white/10 transition-all">
                        {{ __('Legal Counsel') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Smooth scrolling for sidebar links
        document.querySelectorAll('aside nav a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Active state for sidebar links based on scroll position
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('aside nav a');

            let currentSection = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 150) {
                    currentSection = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('bg-purple-500/10', 'border-purple-500/30', 'text-white');
                link.querySelector('span')?.classList.add('opacity-0');

                if (link.getAttribute('href') === `#${currentSection}`) {
                    link.classList.add('bg-purple-500/10', 'border-purple-500/30', 'text-white');
                    link.querySelector('span')?.classList.remove('opacity-0');
                }
            });
        });
    </script>
@endsection
