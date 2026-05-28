@php
    $info = $payment_method->payment_information;
    if (isset($deposit)) {
        $expires_at = $deposit->expires_at;
        // dd($expires_at);
        //$expires_at = is_numeric($expires) ? $expires : \Carbon\Carbon::parse($expires)->timestamp;
    } else {
        $expires_at = $user_payment_details['expires_at'];
    }

    if (isset($deposit)) {
        $info = $deposit->structured_data;
    }
    // Ensure it's an array if not already cast
    if (is_string($info)) {
        $info = json_decode($info, true);
    }
@endphp

@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.deposits.new') }}"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-secondary-dark border border-white/10 text-text-secondary hover:text-white hover:bg-white/5 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-white font-heading">{{ __('Complete Payment') }}</h2>
                    <p class="text-xs text-text-secondary">{{ __('Follow the instructions to complete your deposit') }}</p>
                </div>
            </div>

            {{-- Timer or Status --}}
            <div id="status-section" data-status="{{ $deposit->status ?? 'pending' }}"
                class="flex flex-row sm:flex-col items-center sm:items-end justify-between bg-white/[0.03] sm:bg-transparent p-3 sm:p-0 rounded-2xl border border-white/5 sm:border-none">
                @php
                    $status_color = 'amber';
                    $status_text = __('Pending Payment');
                    if (isset($deposit)) {
                        if ($deposit->status === 'completed') {
                            $status_color = 'green';
                            $status_text = __('Completed');
                        } elseif ($deposit->status === 'failed') {
                            $status_color = 'red';
                            $status_text = __('Failed');
                        } elseif ($deposit->status === 'partial_payment') {
                            $status_color = 'blue';
                            $status_text = __('Partial Payment');
                        }
                    }
                @endphp
                <span
                    class="px-2 py-1 rounded-lg bg-{{ $status_color }}-500/10 border border-{{ $status_color }}-500/20 text-{{ $status_color }}-500 text-[10px] font-bold uppercase tracking-wider">
                    {{ $status_text }}
                </span>

                @if (isset($deposit) && in_array($deposit->status, ['completed', 'partial_payment']))
                    <a href="{{ route('user.deposits.receipt', $deposit->transaction_reference) }}"
                        class="mt-3 flex items-center gap-2 px-3 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-white text-[10px] font-bold uppercase tracking-wider transition-all group">
                        <svg class="w-3.5 h-3.5 text-accent-primary group-hover:scale-110 transition-transform"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        {{ __('Download Receipt') }}
                    </a>
                @endif

                @if (!isset($deposit) || (isset($deposit) && $deposit->status === 'pending'))
                    <div id="countdown-timer" data-expires="{{ $expires_at }}"
                        class="text-white font-mono text-lg font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent-primary animate-[spin_3s_linear_infinite]" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="timer-display">--:--:--</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column: Payment Instructions -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Instruction Card -->
                <div class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-white/5 flex items-center gap-4 bg-white/[0.02]">
                        <div
                            class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 p-2 flex items-center justify-center">
                            @if ($payment_method->logo)
                                <img src="{{ asset('assets/images/deposit-methods/' . $payment_method->logo) }}"
                                    alt="{{ $payment_method->name }}" class="h-full w-full object-contain">
                            @else
                                <span class="text-xl font-bold text-white">{{ substr($payment_method->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">{{ $payment_method->name }}</h3>
                            <p class="text-xs text-text-secondary">{{ __('Payment Method') }}</p>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">


                        @if ($payment_method->type == 'crypto')
                            {{-- Crypto Payment Details --}}
                            <div class="flex flex-col items-center justify-center gap-6">
                                {{-- QR Code --}}
                                @if (!empty($info['wallet_address']))
                                    <div class="p-4 bg-white rounded-xl">
                                        {!! QrCode::size(180)->generate($info['wallet_address']) !!}
                                    </div>
                                @endif

                                <div class="w-full space-y-4">
                                    {{-- Converted Amount (Main Emphasis) --}}
                                    <div
                                        class="bg-[#0f1115] rounded-xl p-4 border border-white/10 mt-4 relative group md:hidden">
                                        <span
                                            class="text-[10px] text-accent-primary uppercase tracking-wider block mb-1 font-bold">{{ __('Exact Amount to Send') }}</span>
                                        <div class="flex flex-col gap-1 mt-1">
                                            <span
                                                class="text-2xl font-bold text-white font-mono tracking-tight break-all leading-tight">
                                                {{ $user_payment_details['converted_amount'] }}
                                            </span>
                                            {{-- {{ dd($user_payment_details) }} --}}
                                            <span
                                                class="text-xs font-bold text-accent-primary uppercase tracking-widest">{{ $info['currency'] ?? getSetting('currency') }}</span>
                                        </div>

                                        <button
                                            class="absolute top-2 right-2 p-2 hover:bg-white/10 rounded-lg text-accent-primary transition-colors copy-btn cursor-pointer"
                                            data-clipboard-text="{{ $user_payment_details['converted_amount'] }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                    {{-- Address --}}
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4 relative group">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Wallet Address') }}</p>
                                        <div class="flex items-center justify-between gap-4">
                                            <code
                                                class="text-sm text-accent-primary font-mono bg-accent-primary/5 px-2 py-1 rounded break-all">{{ $info['wallet_address'] ?? '' }}</code>
                                            <button
                                                class="p-2 hover:bg-white/10 rounded-lg text-text-secondary hover:text-white transition-colors copy-btn cursor-pointer"
                                                data-clipboard-text="{{ $info['wallet_address'] ?? '' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Network --}}


                                    <div class="grid grid-cols-2 gap-4">
                                        @if (!empty($info['network']))
                                            <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                                <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                                    {{ __('Network') }}</p>
                                                <p class="text-white font-bold">{{ $info['network'] }}</p>
                                            </div>
                                        @endif
                                        @if (!empty($info['currency']))
                                            <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                                <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                                    {{ __('Asset') }}</p>
                                                <p class="text-white font-bold">{{ $info['currency'] }}</p>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        @elseif($payment_method->type == 'bank_transfer')
                            {{-- Converted Amount (Main Emphasis) --}}
                            <div class="bg-[#0f1115] rounded-xl p-4 border border-white/10 mt-4 relative group md:hidden">
                                <span
                                    class="text-[10px] text-accent-primary uppercase tracking-wider block mb-1 font-bold">{{ __('Exact Amount to Send') }}</span>
                                <div class="flex flex-col gap-1 mt-1">
                                    <span
                                        class="text-2xl font-bold text-white font-mono tracking-tight break-all leading-tight">
                                        {{ $user_payment_details['converted_amount'] }}
                                    </span>
                                    {{-- {{ dd($user_payment_details) }} --}}
                                    <span
                                        class="text-xs font-bold text-accent-primary uppercase tracking-widest">{{ $info['currency'] ?? getSetting('currency') }}</span>
                                </div>

                                <button
                                    class="absolute top-2 right-2 p-2 hover:bg-white/10 rounded-lg text-accent-primary transition-colors copy-btn cursor-pointer"
                                    data-clipboard-text="{{ $user_payment_details['converted_amount'] }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Bank Transfer Details --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if (!empty($info['bank_name']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Bank Name') }}</p>
                                        <p class="text-white font-bold">{{ $info['bank_name'] }}</p>
                                    </div>
                                @endif
                                @if (!empty($info['account_holder']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Account Holder') }}</p>
                                        <p class="text-white font-bold">{{ $info['account_holder'] }}</p>
                                    </div>
                                @endif
                                @if (!empty($info['account_number']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4 relative group">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Account Number') }}</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-white font-bold font-mono">{{ $info['account_number'] }}</p>
                                            <button
                                                class="opacity-0 group-hover:opacity-100 p-1 hover:bg-white/10 rounded text-text-secondary hover:text-white transition-all copy-btn cursor-pointer"
                                                data-clipboard-text="{{ $info['account_number'] }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($info['routing_number']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Routing Number') }}</p>
                                        <p class="text-white font-bold">{{ $info['routing_number'] }}</p>
                                    </div>
                                @endif
                                @if (!empty($info['swift']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('SWIFT / BIC') }}</p>
                                        <p class="text-white font-bold">{{ $info['swift'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @elseif($payment_method->type == 'digital_wallet')
                            {{-- Digital Wallet Details --}}
                            <div class="space-y-4">
                                {{-- Converted Amount (Main Emphasis) --}}
                                <div
                                    class="bg-[#0f1115] rounded-xl p-4 border border-white/10 mt-4 relative group md:hidden">
                                    <span
                                        class="text-[10px] text-accent-primary uppercase tracking-wider block mb-1 font-bold">{{ __('Exact Amount to Send') }}</span>
                                    <div class="flex flex-col gap-1 mt-1">
                                        <span
                                            class="text-2xl font-bold text-white font-mono tracking-tight break-all leading-tight">
                                            {{ $user_payment_details['converted_amount'] }}
                                        </span>
                                        {{-- {{ dd($user_payment_details) }} --}}
                                        <span
                                            class="text-xs font-bold text-accent-primary uppercase tracking-widest">{{ $info['currency'] ?? getSetting('currency') }}</span>
                                    </div>

                                    <button
                                        class="absolute top-2 right-2 p-2 hover:bg-white/10 rounded-lg text-accent-primary transition-colors copy-btn cursor-pointer"
                                        data-clipboard-text="{{ $user_payment_details['converted_amount'] }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                @if (!empty($info['email']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4 relative group">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Email Address') }}</p>
                                        <div class="flex items-center justify-between">
                                            <p class="text-white font-bold">{{ $info['email'] }}</p>
                                            <button
                                                class="opacity-0 group-hover:opacity-100 p-1 hover:bg-white/10 rounded text-text-secondary hover:text-white transition-all copy-btn cursor-pointer"
                                                data-clipboard-text="{{ $info['email'] }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($info['username']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Username') }}</p>
                                        <p class="text-white font-bold">{{ $info['username'] }}</p>
                                    </div>
                                @endif
                                @if (!empty($info['tag']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Tag / Handle') }}</p>
                                        <p class="text-white font-bold">{{ $info['tag'] }}</p>
                                    </div>
                                @endif
                                @if (!empty($info['phone']))
                                    <div class="bg-[#0f1115] border border-white/10 rounded-xl p-4">
                                        <p class="text-[10px] uppercase font-bold text-text-secondary mb-1">
                                            {{ __('Phone Number') }}</p>
                                        <p class="text-white font-bold">{{ $info['phone'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            {{-- Fallback --}}
                            <div class="p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl text-amber-500 text-sm">
                                {{ __('Do not send payment if the status is expired.') }}
                            </div>
                        @endif

                        {{-- Instructions --}}
                        <div class="p-6 bg-[#0f1115] rounded-xl border border-white/5">
                            <h4 class="text-sm font-bold text-white mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-accent-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('Instructions') }}
                            </h4>
                            <div class="prose prose-invert prose-sm max-w-none text-text-secondary leading-relaxed">
                                @if (!empty($info['instructions']))
                                    {!! $info['instructions'] !!}
                                @else
                                    <p class="italic opacity-50">{{ __('Do not send payment if the status is expired.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Helper Tip -->
                        <div
                            class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 flex gap-3 text-blue-400 text-xs">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p>{{ __('Please ensure you transfer the exact total amount shown to the details above.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary & Confirmation -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <div
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden relative group">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>

                    <div class="p-6 border-b border-white/5">
                        <h3 class="font-bold text-white">{{ __('Payment Summary') }}</h3>
                    </div>

                    <div class="p-6 space-y-4 relative z-10">
                        <div class="p-6 space-y-4 relative z-10">
                            <div class="flex justify-between text-sm">
                                <span class="text-text-secondary">{{ __('Amount') }}</span>
                                <span
                                    class="text-white font-mono font-medium">{{ number_format($user_payment_details['amount'], 2) }}
                                    {{ getSetting('currency') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-text-secondary">{{ __('Fee') }}
                                    ({{ $user_payment_details['fee_percent'] }}%)</span>
                                <span
                                    class="text-white font-mono font-medium">+{{ number_format($user_payment_details['fee_amount'], 2) }}
                                    {{ getSetting('currency') }}</span>
                            </div>

                            <div class="h-px bg-white/10 my-2"></div>

                            <div class="flex justify-between items-center text-sm opacity-70">
                                <span class="text-text-secondary">{{ __('Equivalent Value') }}</span>
                                <span
                                    class="text-white font-mono">{{ number_format($user_payment_details['total_amount'], 2) }}
                                    {{ getSetting('currency') }}</span>
                            </div>

                            {{-- Converted Amount (Main Emphasis) --}}
                            <div
                                class="bg-accent-primary/10 rounded-xl p-4 border border-accent-primary/20 mt-4 relative group">
                                <span
                                    class="text-[10px] text-accent-primary uppercase tracking-wider block mb-1 font-bold">{{ __('Exact Amount to Send') }}</span>
                                <div class="flex flex-col gap-1 mt-1">
                                    <span
                                        class="text-2xl font-bold text-white font-mono tracking-tight break-all leading-tight">
                                        {{ $user_payment_details['converted_amount'] }}
                                    </span>
                                    {{-- {{ dd($user_payment_details) }} --}}
                                    <span
                                        class="text-xs font-bold text-accent-primary uppercase tracking-widest">{{ $info['currency'] ?? getSetting('currency') }}</span>
                                </div>

                                <button
                                    class="absolute top-2 right-2 p-2 hover:bg-white/10 rounded-lg text-accent-primary transition-colors copy-btn cursor-pointer"
                                    data-clipboard-text="{{ $user_payment_details['converted_amount'] }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Form -->
                <div id="action-form-section"
                    class="bg-secondary-dark/40 backdrop-blur-md border border-white/10 rounded-2xl p-6 @if ($payment_method->class == 'automatic') hidden @endif  @if (isset($deposit) && $deposit->status !== 'pending') hidden @endif">
                    <form action="{{ route('user.deposits.new.manual-validate') }}" method="POST"
                        class="ajax-form text-left" enctype="multipart/form-data" data-action="redirect">
                        @csrf
                        <div class="space-y-4">
                            @if ($payment_method->type == 'crypto')
                                <!-- Transaction ID -->
                                <div>
                                    <label
                                        class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">{{ __('Transaction ID / Hash') }}</label>
                                    <input type="text" name="transaction_hash" required
                                        class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white text-sm focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all placeholder-white/20"
                                        placeholder="{{ __('Enter transaction ID') }}">
                                </div>
                            @endif


                            <!-- Proof -->
                            <div>
                                <label
                                    class="block text-xs font-bold text-text-secondary uppercase tracking-wider mb-2">{{ __('Payment Proof') }}</label>
                                <div class="relative group">
                                    <input type="file" name="proof" id="proof_file"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        accept="image/*" required>
                                    <label for="proof_file"
                                        class="relative flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-white/10 rounded-xl cursor-pointer hover:border-accent-primary/50 hover:bg-white/5 transition-all overflow-hidden">

                                        {{-- Default State --}}
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6 transition-opacity duration-300"
                                            id="upload-placeholder">
                                            <svg class="w-8 h-8 mb-3 text-text-secondary group-hover:text-white transition-colors"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="mb-2 text-xs text-text-secondary"><span
                                                    class="font-bold text-white">{{ __('Click to upload') }}</span>
                                            </p>
                                            <p class="text-[10px] text-text-secondary/60">
                                                {{ __('PNG, JPG (MAX. 5MB)') }}</p>
                                        </div>

                                        {{-- Preview State --}}
                                        <div id="file-preview-container" class="absolute inset-0 hidden">
                                            <img id="file-preview-image" src="" alt="Preview"
                                                class="w-full h-full object-cover opacity-60">
                                            <div
                                                class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <p
                                                    class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full backdrop-blur-sm border border-white/20">
                                                    {{ __('Click to change') }}</p>
                                            </div>
                                        </div>
                                    </label>
                                    <div id="file-name" class="mt-2 text-xs text-accent-primary text-center hidden">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-6 space-y-3">
                            <button type="submit"
                                class="w-full py-3.5 bg-accent-primary hover:bg-accent-primary/90 text-white font-bold rounded-xl shadow-[0_0_20px_rgba(var(--color-accent-primary),0.3)] hover:shadow-[0_0_25px_rgba(var(--color-accent-primary),0.5)] transition-all transform active:scale-[0.98] cursor-pointer">
                                {{ __('Confirm Payment') }}
                            </button>

                            <button type="button" id="cancel-btn"
                                class="w-full py-3.5 bg-secondary-dark border border-white/10 hover:bg-white/5 text-text-secondary hover:text-white font-medium rounded-xl transition-all cursor-pointer">
                                {{ __('Cancel Transaction') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // File Upload Preview Text
            // File Upload Preview Logic
            $('#proof_file').change(function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#file-preview-image').attr('src', e.target.result);
                        $('#file-preview-container').removeClass('hidden');
                        $('#upload-placeholder').addClass(
                            'opacity-0'); // Hide placeholder visually but keep layout
                    }
                    reader.readAsDataURL(file);

                    $('#file-name').text(file.name).removeClass('hidden');
                }
            });


            // Copy Button Logic
            $('.copy-btn').click(function(e) {
                e.preventDefault();
                var text = $(this).data('clipboard-text');
                navigator.clipboard.writeText(text).then(function() {
                    toastNotification('{{ __('Copied to clipboard') }}', 'success');
                }, function(err) {
                    toastNotification('{{ __('Failed to copy') }}', 'error');
                });
            });

            // Cancel Handler
            $('#cancel-btn').click(function() {
                Swal.fire({
                    title: '{{ __('Cancel Deposit?') }}',
                    text: '{{ __('Are you sure you want to cancel this deposit request?') }}',
                    icon: 'warning',
                    background: '#1f1f22',
                    color: '#ffffff',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#ffffff10',
                    confirmButtonText: '{{ __('Yes, Cancel') }}',
                    cancelButtonText: '{{ __('No, Keep') }}',
                    customClass: {
                        popup: 'border border-white/10 shadow-xl rounded-xl',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('user.deposits.new.manual-cancel') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    toastNotification(response.message, 'success');
                                    setTimeout(() => window.location.href = response
                                        .redirect, 1000);
                                } else {
                                    toastNotification(response.message ||
                                        'Error occurred',
                                        'error');
                                }
                            },
                            error: function() {
                                toastNotification('{{ __('Something went wrong') }}',
                                    'error');
                            }
                        });
                    }
                });
            });

            // Background Status Update
            let statusInterval;

            function checkStatusUpdate() {
                const currentStatus = $('#status-section').data('status');

                // Only poll if pending or partial payment
                if (currentStatus !== 'pending' && currentStatus !== 'partial_payment') {
                    if (statusInterval) clearInterval(statusInterval);
                    return;
                }

                $.get(window.location.href, function(data) {
                    const $newData = $(data);
                    const $newStatusSection = $newData.find('#status-section');

                    // Update Status Section
                    const newStatusHtml = $newStatusSection.html();
                    const newStatus = $newStatusSection.data('status');

                    if (newStatusHtml && $('#status-section').html().trim() !== newStatusHtml.trim()) {
                        $('#status-section').html(newStatusHtml);
                        $('#status-section').data('status', newStatus); // Sync status
                        initCountdown(); // Re-initialize if timer is present

                        // Stop polling if reached final status
                        if (newStatus !== 'pending' && newStatus !== 'partial_payment') {
                            clearInterval(statusInterval);
                        }
                    }

                    // Update Action Form Section
                    const newFormHtml = $newData.find('#action-form-section').html();
                    if (newFormHtml && $('#action-form-section').html().trim() !== newFormHtml.trim()) {
                        $('#action-form-section').html(newFormHtml);
                    }
                });
            }

            // Check every 5 seconds
            statusInterval = setInterval(checkStatusUpdate, 5000);

            // Countdown Timer Logic
            let timerInterval;

            function initCountdown() {
                const $timer = $('#countdown-timer');
                const $display = $('#timer-display');
                if (!$timer.length) {
                    if (timerInterval) clearInterval(timerInterval);
                    return;
                }

                if (timerInterval) clearInterval(timerInterval);

                const expiresAt = parseInt($timer.data('expires')) * 1000; // Convert to ms

                function updateTimer() {
                    const now = new Date().getTime();
                    const distance = expiresAt - now;

                    if (distance < 0) {
                        $display.text('EXPIRED').addClass('text-red-500');
                        $timer.find('svg').removeClass('animate-spin-slow').addClass('text-red-500');
                        clearInterval(timerInterval);
                        return;
                    }

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Format with leading zeros
                    const h = String(hours).padStart(2, '0');
                    const m = String(minutes).padStart(2, '0');
                    const s = String(seconds).padStart(2, '0');

                    $display.text(`${h}:${m}:${s}`);

                    // Warning colors
                    if (distance < 300000) { // < 5 mins
                        $display.addClass('text-red-500');
                    } else if (distance < 900000) { // < 15 mins
                        $display.addClass('text-amber-500');
                    }
                }

                updateTimer();
                timerInterval = setInterval(updateTimer, 1000);
            }

            initCountdown();
        });
    </script>
@endsection
