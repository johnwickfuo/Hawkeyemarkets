@extends('templates.' . config('site.template') . '.blades.layouts.front')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative pt-32 pb-24 overflow-hidden bg-[#050505]">
        {{-- Atmospheric Background --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(59,130,246,0.08),transparent_70%)]">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:60px_60px]">
            </div>
            <div
                class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cfilter id=%22noiseFilter%22%3E%3CfeTurbulence type=%22fractalNoise%22 baseFrequency=%220.65%22 numOctaves=%223%22 stitchTiles=%22stitch%22/%3E%3C/filter%3E%3Crect width=%22100%25%22 height=%22100%25%22 filter=%22url(%23noiseFilter)%22/%3E%3C/svg%3E')] opacity-[0.03] pointer-events-none">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent-primary/10 border border-accent-primary/20 mb-8 animate-fade-in-up">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-primary animate-pulse"></span>
                    <span
                        class="text-[10px] font-black text-accent-primary uppercase tracking-widest">{{ __('Data Sovereignty') }}</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-8 leading-tight tracking-tighter">
                    {{ __('Privacy') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Protocols.') }}</span>
                </h1>
                <p class="text-xl text-text-secondary leading-relaxed mb-12 max-w-2xl mx-auto">
                    {{ __('At :site_name, your data is treated with the same rigor as institutional capital. We leverage cryptographic standards to ensure your financial footprint remains yours and yours alone.', ['site_name' => getSetting('name')]) }}
                </p>
            </div>
        </div>
    </section>

    {{-- CORE PRINCIPLES --}}
    <section class="py-24 relative overflow-hidden bg-[#050505] border-t border-white/5">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Principle 1: Encryption --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-accent-primary/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-accent-primary mb-8 group-hover:bg-accent-primary group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Advanced Encryption') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('We utilize AES-256 bank-grade encryption for all PII data, ensuring that your sensitive information is unreadable even in the event of an infrastructure breach.') }}
                    </p>
                </div>

                {{-- Principle 2: Zero-Sharing --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-accent-primary/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-accent-primary mb-8 group-hover:bg-accent-primary group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Zero Data Monetization') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __(':site_name never sells user data to third-party brokers or advertisers. Our revenue model is transparent and strictly service-fee based.', ['site_name' => getSetting('name')]) }}
                    </p>
                </div>

                {{-- Principle 3: Compliance --}}
                <div
                    class="p-10 rounded-[2.5rem] bg-[#0B0F17]/40 border border-white/5 hover:border-accent-primary/30 transition-all duration-500 group">
                    <div
                        class="w-14 h-14 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-accent-primary mb-8 group-hover:bg-accent-primary group-hover:text-white transition-all duration-500">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">{{ __('Global Standards') }}</h3>
                    <p class="text-text-secondary text-sm leading-relaxed">
                        {{ __('We strictly adhere to GDPR, CCPA, and international financial privacy laws to provide a secure and legally robust experience for global users.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- DETAILED CONTENT --}}
    <section class="py-24 relative overflow-hidden bg-black/20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-invert prose-emerald max-w-none">
                    <div class="space-y-16">
                        {{-- Section 1 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-accent-primary rounded-full"></span>
                                {{ __('1. Information Collection') }}
                            </h2>
                            <div class="text-text-secondary leading-relaxed space-y-4">
                                <p>{{ __('We collect information that you provide directly to us when you create an account, complete a transaction, or communicate with us.') }}
                                </p>
                                <ul class="list-disc pl-6 space-y-2">
                                    <li>{{ __('Personal Identification: Name, email address, phone number, and government-issued ID.') }}
                                    </li>
                                    <li>{{ __('Financial Information: Wallet addresses, transaction history, and bank account details.') }}
                                    </li>
                                    <li>{{ __('Technical Data: IP addresses, device identifiers, and browser fingerprints used for fraud prevention.') }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Section 2 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-accent-primary rounded-full"></span>
                                {{ __('2. Use of Information') }}
                            </h2>
                            <div class="text-text-secondary leading-relaxed space-y-4">
                                <p>{{ __('Your information is used solely to provide and improve our services, including:') }}
                                </p>
                                <ul class="list-disc pl-6 space-y-2">
                                    <li>{{ __('Processing transactions and managing your investment portfolio.') }}</li>
                                    <li>{{ __('Verification of identity to comply with AML/KYC regulations.') }}</li>
                                    <li>{{ __('Enhancing platform security and preventing unauthorized access.') }}</li>
                                    <li>{{ __('Communication regarding platform updates and security alerts.') }}</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Section 3 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-accent-primary rounded-full"></span>
                                {{ __('3. Data Retention') }}
                            </h2>
                            <p class="text-text-secondary leading-relaxed">
                                {{ __('We retain your personal information only for as long as necessary to fulfill the purposes for which it was collected, including legal, accounting, or reporting requirements. When data is no longer required, it is securely purged from our systems using military-grade deletion protocols.') }}
                            </p>
                        </div>

                        {{-- Section 4 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-accent-primary rounded-full"></span>
                                {{ __('4. Your Rights') }}
                            </h2>
                            <p class="text-text-secondary leading-relaxed">
                                {{ __('You have the right to access, correct, or delete your personal data. You may also object to or restrict certain processing activities. To exercise these rights, please contact our Data Protection Officer through the command center.') }}
                            </p>
                        </div>

                        {{-- Section 5 --}}
                        <div class="space-y-6">
                            <h2 class="text-3xl font-black text-white flex items-center gap-4">
                                <span class="w-2 h-8 bg-accent-primary rounded-full"></span>
                                {{ __('5. Cookies & Tracking') }}
                            </h2>
                            <p class="text-text-secondary leading-relaxed">
                                {{ __(':site_name uses session cookies and diagnostic tools to maintain your authenticated state and optimize platform performance. We do not use persistent tracking cookies for cross-site advertising.', ['site_name' => getSetting('name')]) }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-20 p-8 rounded-3xl bg-accent-primary/5 border border-accent-primary/10 text-center">
                        <p class="text-sm font-bold text-accent-primary uppercase tracking-widest mb-2">
                            {{ __('Last Updated') }}
                        </p>
                        <p class="text-white font-black">
                            {{ date('F d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 relative overflow-hidden bg-[#050505] border-t border-white/5">
        <div class="container mx-auto px-4 relative z-10 text-center">
            <div
                class="max-w-4xl mx-auto p-12 lg:p-20 rounded-[3rem] bg-gradient-to-b from-white/[0.05] to-transparent border border-white/5 relative overflow-hidden">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-8 leading-tight">
                    {{ __('Secure Your') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Financial Future.') }}</span>
                </h2>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('user.register') }}"
                        class="px-10 py-5 bg-accent-primary text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-accent-primary/80 transition-all shadow-lg shadow-accent-primary/20 hover:-translate-y-1">
                        {{ __('Open Account') }}
                    </a>
                    <a href="{{ route('contact') }}"
                        class="px-10 py-5 bg-white/5 border border-white/10 text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-white/10 transition-all">
                        {{ __('Contact Compliance') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
