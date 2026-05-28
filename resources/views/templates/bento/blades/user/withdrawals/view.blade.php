@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="space-y-8">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('user.withdrawals.index') }}"
                    class="p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-text-secondary hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18">
                        </path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ __('Withdrawal Details') }}</h1>
                    <p class="text-text-secondary text-sm mt-1">{{ __('Transaction') }}
                        #{{ $withdrawal->transaction_reference }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $statusClasses = [
                        'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                        'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                        'failed' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        'partial_payment' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                    ];
                    $class = $statusClasses[$withdrawal->status] ?? 'bg-white/10 text-white border-white/20';
                @endphp
                <span class="px-4 py-2 rounded-xl border {{ $class }} font-bold uppercase tracking-wider text-sm">
                    {{ __($withdrawal->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Info --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Financial Summary --}}
                <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-6">{{ __('Withdrawal Summary') }}</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-text-secondary">{{ __('Amount Requested') }}</span>
                            <span class="text-white font-bold">{{ number_format($withdrawal->amount, 2) }}
                                {{ getSetting('currency') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-text-secondary">{{ __('Processing Fee') }}
                                <span class="text-xs">({{ number_format($withdrawal->fee_percent, 2) }}%)</span></span>
                            <span class="text-red-400 font-medium">- {{ number_format($withdrawal->fee_amount, 2) }}
                                {{ getSetting('currency') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-white/5">
                            <span class="text-text-secondary">{{ __('Net Amount Payable') }}</span>
                            <span class="text-xl font-bold text-white">{{ number_format($withdrawal->amount_payable, 2) }}
                                {{ getSetting('currency') }}</span>
                        </div>

                        @if ($withdrawal->exchange_rate != 1)
                            <div class="bg-white/5 rounded-xl p-4 mt-4 space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-text-secondary">{{ __('Exchange Rate') }}</span>
                                    <span class="text-white">1 {{ getSetting('currency') }} =
                                        {{ number_format($withdrawal->exchange_rate, 8) }}
                                        {{ $withdrawal->currency }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-white/10">
                                    <span class="text-text-secondary font-medium">{{ __('Converted Amount') }}</span>
                                    <span class="text-lg font-bold text-accent-primary">
                                        {{ number_format($withdrawal->converted_amount, 8) }} {{ $withdrawal->currency }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Withdrawal Method Details --}}
                <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-6">{{ __('Payment Credentials') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6">
                        <div class="space-y-1">
                            <p class="text-xs text-text-secondary uppercase tracking-wider">{{ __('Method') }}</p>
                            <p class="text-white font-medium">{{ $withdrawal->withdrawalMethod->name ?? __('Unknown') }}
                            </p>
                        </div>

                        @if ($withdrawal->transaction_hash)
                            <div class="space-y-1 md:col-span-2">
                                <p class="text-xs text-text-secondary uppercase tracking-wider">
                                    {{ __('Transaction Hash') }}</p>
                                <div class="flex items-center gap-2">
                                    <p
                                        class="text-white font-mono text-sm break-all bg-black/20 p-2 rounded-lg border border-white/5 w-full">
                                        {{ $withdrawal->transaction_hash }}
                                    </p>
                                    <button
                                        class="p-2 hover:bg-white/10 rounded-lg text-text-secondary transition-colors copy-btn"
                                        data-clipboard-text="{{ $withdrawal->transaction_hash }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Structured Data Loop --}}
                    @php
                        $raw_structure = $withdrawal->getAttributes()['structured_data'] ?? null;
                        $details = null;
                        if (is_array($raw_structure)) {
                            $details = $raw_structure;
                        } elseif (is_string($raw_structure)) {
                            $details = json_decode($raw_structure, true);
                        }
                    @endphp

                    @if ($details && is_array($details))
                        <div class="pt-6 border-t border-white/5 grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($details as $key => $value)
                                @continue($key === 'transaction_hash') {{-- Skip hash --}}
                                @continue(is_null($value)) {{-- Skip null values --}}
                                <div class="space-y-1">
                                    <p class="text-xs text-text-secondary uppercase tracking-wider">
                                        {{ ucwords(str_replace('_', ' ', $key)) }}</p>
                                    <p class="text-white font-medium break-all">
                                        {{ is_array($value) ? json_encode($value) : $value }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Payment Proof --}}
                @if ($withdrawal->payment_proof)
                    <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">{{ __('Payment Proof') }}</h3>
                        <div
                            class="rounded-xl overflow-hidden border border-white/10 bg-black/20 group relative h-48 md:h-auto">
                            <img src="{{ asset('storage/' . $withdrawal->payment_proof) }}" alt="Proof"
                                class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                            <div
                                class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                                <a href="{{ asset('storage/' . $withdrawal->payment_proof) }}" target="_blank"
                                    class="px-4 py-2 bg-white text-black font-bold rounded-lg transform scale-90 group-hover:scale-100 transition-transform">
                                    {{ __('View Full Image') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Timeline --}}
                <div class="bg-secondary border border-white/5 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-white mb-4">{{ __('Timeline') }}</h3>
                    <div class="relative pl-4 border-l-2 border-white/10 ml-2 space-y-8">
                        <div class="relative">
                            <div
                                class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-accent-primary ring-4 ring-secondary">
                            </div>
                            <p class="text-xs text-text-secondary uppercase tracking-wider">{{ __('Requested') }}</p>
                            <p class="text-white font-bold text-sm mt-1">{{ $withdrawal->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-text-secondary text-xs">{{ $withdrawal->created_at->format('H:i A') }}</p>
                        </div>

                        <div class="relative">
                            <div
                                class="absolute -left-[21px] top-1 w-3 h-3 rounded-full {{ $withdrawal->status === 'completed' ? 'bg-emerald-500' : 'bg-white/20' }} ring-4 ring-secondary">
                            </div>
                            <p class="text-xs text-text-secondary uppercase tracking-wider">{{ __('Last Status Update') }}
                            </p>
                            <p class="text-white font-bold text-sm mt-1">{{ $withdrawal->updated_at->format('M d, Y') }}
                            </p>
                            <p class="text-text-secondary text-xs">{{ $withdrawal->updated_at->format('H:i A') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Support CTA --}}
                <div
                    class="bg-gradient-to-br from-accent-primary/20 to-purple-500/10 border border-accent-primary/20 rounded-2xl p-6 text-center">
                    <div
                        class="w-12 h-12 rounded-full bg-accent-primary/20 text-accent-primary flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-white font-bold mb-2">{{ __('Need Help?') }}</p>
                    <p class="text-text-secondary text-xs mb-4 leading-relaxed">
                        {{ __('If you have any questions regarding this withdrawal, please contact our 24/7 support team.') }}
                    </p>
                    <a href="{{ route('contact') }}"
                        class="inline-block px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
                        {{ __('Open Ticket') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
