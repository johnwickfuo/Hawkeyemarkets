@extends('templates.' . config('site.template') . '.blades.layouts.front')

@section('content')
    {{-- HERO SECTION --}}
    <section class="relative pt-40 pb-24 overflow-hidden bg-[#050505]">
        {{-- Atmospheric Background --}}
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-[radial-gradient(circle_at_50%_0%,rgba(59,130,246,0.08),transparent_70%)]">
            </div>
            <div
                class="absolute inset-0 bg-[linear-gradient(to_right,#ffffff02_1px,transparent_1px),linear-gradient(to_bottom,#ffffff02_1px,transparent_1px)] bg-[size:60px_60px]">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent-primary/10 border border-accent-primary/20 mb-8 animate-fade-in-up">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent-primary animate-pulse"></span>
                    <span
                        class="text-[10px] font-black text-accent-primary uppercase tracking-widest">{{ __('Global Support Network') }}</span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white mb-8 leading-tight tracking-tighter">
                    {{ __('Get in') }} <br>
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-accent-primary to-accent-secondary">{{ __('Touch.') }}</span>
                </h1>
                <p class="text-xl text-text-secondary leading-relaxed mb-12 max-w-2xl mx-auto">
                    {{ __('Our specialist advisors are available 24/7 to assist with institutional inquiries, technical support, and partnership opportunities.') }}
                </p>
            </div>
        </div>
    </section>


    {{-- CONTACT FORM --}}
    <section class="py-24 relative overflow-hidden bg-[#050505]">
        <div class="container mx-auto px-4 relative z-10">
            <div
                class="max-w-6xl mx-auto rounded-[3rem] bg-[#0B0F17]/60 border border-white/5 overflow-hidden backdrop-blur-3xl shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    {{-- Form Side --}}
                    <div class="p-10 lg:p-20">
                        <h2 class="text-4xl font-black text-white mb-8">{{ __('Send a') }} <span
                                class="text-accent-primary">{{ __('Message.') }}</span></h2>
                        <form action="{{ route('contact.send') }}" method="POST" class="ajax-form space-y-8"
                            data-action="reset">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-text-secondary">{{ __('Full Name') }}</label>
                                    <input type="text" name="name" placeholder="John Doe" required
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-text-secondary">{{ __('Email Address') }}</label>
                                    <input type="email" name="email" placeholder="john@example.com" required
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary transition-all">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-text-secondary">{{ __('Subject') }}</label>
                                <input type="text" name="subject" placeholder="{{ __('Advisory Inquiry') }}" required
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary transition-all">
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black uppercase tracking-widest text-text-secondary">{{ __('Message Content') }}</label>
                                <textarea name="message" rows="5" placeholder="{{ __('Describe your inquiry in detail...') }}" required
                                    class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary transition-all resize-none"></textarea>
                            </div>

                            @if (getSetting('google_recaptcha') == 'enabled')
                                <div class="pt-2">
                                    {!! NoCaptcha::display(['data-theme' => 'dark']) !!}
                                    @error('g-recaptcha-response')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <button type="submit"
                                class="w-full py-5 bg-accent-primary text-white rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-accent-primary/80 transition-all shadow-lg shadow-accent-primary/20 hover:-translate-y-1 cursor-pointer">
                                {{ __('Dispatch Message') }}
                            </button>
                        </form>
                    </div>

                    {{-- Info Side --}}
                    <div class="relative hidden lg:block overflow-hidden bg-[#0A0A0C]">
                        <div
                            class="absolute inset-0 bg-[radial-gradient(circle_at_100%_0%,rgba(59,130,246,0.1),transparent_70%)]">
                        </div>
                        <div class="p-20 relative z-10 flex flex-col h-full">
                            <div class="flex-1">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-8">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span
                                        class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ __('Live Status: Online') }}</span>
                                </div>
                                <h3 class="text-3xl font-black text-white mb-8 leading-tight">
                                    {{ __('Institutional Scale Support') }}<br>
                                    <span class="text-white/40">{{ __('Everywhere, Anytime.') }}</span>
                                </h3>
                                <div class="space-y-8">
                                    @php
                                        $raw_offices = getSetting('offices');
                                        $offices = is_string($raw_offices)
                                            ? json_decode($raw_offices, true)
                                            : $raw_offices;
                                        if (!is_array($offices)) {
                                            $offices = [];
                                        }
                                    @endphp

                                    @foreach ($offices as $office)
                                        <div class="flex items-start gap-6 group">
                                            <div
                                                class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 shrink-0 group-hover:border-accent-primary/50 group-hover:bg-accent-primary/10 transition-all duration-300">
                                                <svg class="w-5 h-5 text-slate-400 group-hover:text-accent-primary transition-colors"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                    </path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex flex-col gap-1">
                                                <h4
                                                    class="text-white font-bold text-sm tracking-wide group-hover:text-accent-primary transition-colors">
                                                    {{ $office['name'] ?? 'Global Office' }}</h4>

                                                @if (!empty($office['address']))
                                                    <p
                                                        class="text-text-secondary text-xs leading-relaxed flex items-center gap-2">
                                                        <span>{{ $office['address'] }}</span>
                                                    </p>
                                                @endif

                                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                                                    @if (!empty($office['email']))
                                                        <a href="mailto:{{ $office['email'] }}"
                                                            class="text-xs text-text-secondary hover:text-white flex items-center gap-1.5 transition-colors">
                                                            <svg class="w-3 h-3 opacity-70" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            {{ $office['email'] }}
                                                        </a>
                                                    @endif

                                                    @if (!empty($office['phone']))
                                                        <a href="tel:{{ $office['phone'] }}"
                                                            class="text-xs text-text-secondary hover:text-white flex items-center gap-1.5 transition-colors">
                                                            <svg class="w-3 h-3 opacity-70" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                                </path>
                                                            </svg>
                                                            {{ $office['phone'] }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if (empty($offices))
                                        <div class="p-6 rounded-2xl border border-white/5 bg-white/[0.02] text-center">
                                            <p class="text-text-secondary text-xs">
                                                {{ __('Headquarters locations will be displayed here.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Decorative Element --}}
                            <div
                                class="mt-20 p-8 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm relative group overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-accent-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <p class="text-sm text-white font-medium relative z-10">
                                    {{ __('":site_name support doesn\'t just answer questions; they provide strategic clarity."', ['site_name' => getSetting('name')]) }}
                                </p>
                                <div class="mt-4 flex items-center gap-3 relative z-10">
                                    <div
                                        class="w-8 h-8 rounded-full bg-accent-primary/20 flex items-center justify-center text-[10px] text-accent-primary font-bold">
                                        AV</div>
                                    <span
                                        class="text-[10px] text-text-secondary font-black uppercase tracking-widest">Advisory
                                        Board</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (getSetting('google_recaptcha') == 'enabled')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush
