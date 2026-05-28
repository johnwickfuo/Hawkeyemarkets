@extends('templates.bento.blades.layouts.user')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <a href="{{ route('user.capital-instruments.bonds') }}"
            class="inline-flex items-center gap-2 text-text-secondary hover:text-white mb-6 transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            {{ __('Back to Marketplace') }}
        </a>

        <div class="bg-secondary-dark/60 backdrop-blur-md border border-white/5 rounded-3xl overflow-hidden">
            <div class="p-8 border-b border-white/5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $bond['name'] }}</h1>
                        <p class="text-text-secondary flex items-center gap-2">
                            <span class="bg-white/5 px-2 py-1 rounded text-xs font-bold">{{ $bond['cusip'] }}</span>
                            <span>{{ $bond['issuer'] }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-bold text-text-secondary uppercase mb-1">{{ __('Coupon Rate') }}</div>
                        <div class="text-3xl font-mono font-bold text-emerald-400">{{ $bond['coupon'] }}%</div>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="bg-white/5 rounded-2xl p-6 border border-white/5">
                        <h3 class="text-xs font-bold text-text-secondary uppercase mb-4">{{ __('Investment Terms') }}</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-text-secondary">{{ __('Maturity Date') }}</span>
                                <span class="text-white font-bold">{{ date('M d, Y', $bond['maturity']) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-text-secondary">{{ __('Investment Period') }}</span>
                                <span
                                    class="text-white font-bold">{{ \Carbon\Carbon::createFromTimestamp($bond['maturity'])->diffForHumans(null, true) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-text-secondary">{{ __('Payout Frequency') }}</span>
                                <span class="text-white font-bold">{{ __('At Maturity') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-500/5 border border-amber-500/10 rounded-2xl p-6">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-amber-500 mb-1">{{ __('Locked Investment') }}</h4>
                                <p class="text-xs text-text-secondary leading-relaxed">
                                    {{ __('Funds invested in this bond are locked until maturity. Early withdrawal or secondary market selling is not available.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#0f1219] rounded-2xl p-8 border border-white/5 self-start">
                    <form id="purchase-form" onsubmit="handlePurchase(event)">
                        @csrf
                        <div class="mb-6">
                            <label
                                class="block text-xs font-bold text-text-secondary uppercase mb-2">{{ __('Investment Amount') }}</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-text-secondary font-mono">{{ getSetting('currency_symbol') }}</span>
                                <input type="number" name="amount" id="amount" step="0.01" required
                                    oninput="calculateReturn()"
                                    class="w-full bg-secondary-dark border border-white/10 rounded-xl py-4 pl-10 pr-4 text-2xl font-mono text-white focus:border-accent-primary transition-all">
                            </div>
                            <p class="text-[10px] text-text-secondary mt-2 flex justify-between">
                                <span>{{ __('Min:') }}
                                    {{ number_format(getSetting('min_bond_purchase', 100), 2) }}</span>
                                <span>{{ __('Balance:') }} {{ number_format(auth()->user()->balance, 2) }}</span>
                            </p>
                        </div>

                        <div class="space-y-3 mb-8 p-4 bg-white/5 rounded-xl border border-white/5">
                            <div class="flex justify-between text-xs">
                                <span class="text-text-secondary">{{ __('Expected Capital Back') }}</span>
                                <span class="text-white font-mono" id="label-principal">0.00</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-text-secondary">{{ __('Expected Interest') }}</span>
                                <span class="text-emerald-400 font-mono font-bold" id="label-interest">+0.00</span>
                            </div>
                            <div class="h-px bg-white/5 my-2"></div>
                            <div class="flex justify-between text-sm">
                                <span class="text-text-secondary font-bold">{{ __('Total Maturity Payout') }}</span>
                                <span class="text-white font-mono font-bold" id="label-total">0.00</span>
                            </div>
                        </div>

                        <button type="submit" id="submit-btn"
                            class="w-full bg-accent-primary hover:bg-accent-primary/90 text-white font-bold py-4 rounded-xl shadow-lg shadow-accent-primary/20 transition-all flex items-center justify-center gap-3 cursor-pointer">
                            <span>{{ __('Confirm Investment') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const coupon = {{ (float) $bond['coupon'] }};
        const maturity = {{ (int) $bond['maturity'] }};
        const now = {{ time() }};
        const currencySymbol = "{{ getSetting('currency_symbol') }}";

        function calculateReturn() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const remainingSeconds = maturity - now;
            const years = remainingSeconds / (365 * 24 * 60 * 60);
            const interest = amount * (coupon / 100) * years;
            const total = amount + interest;

            document.getElementById('label-principal').textContent = amount.toLocaleString(undefined, {
                minimumFractionDigits: 2
            });
            document.getElementById('label-interest').textContent = '+' + interest.toLocaleString(undefined, {
                minimumFractionDigits: 2
            });
            document.getElementById('label-total').textContent = total.toLocaleString(undefined, {
                minimumFractionDigits: 2
            });
        }

        async function handlePurchase(e) {
            e.preventDefault();
            const btn = document.getElementById('submit-btn');
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-spin text-xl">⏳</span>';

            const formData = new FormData(e.target);
            try {
                const response = await fetch(
                    "{{ route('user.capital-instruments.bonds.buy-validate', $bond['cusip']) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                const data = await response.json();
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Something went wrong');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            } catch (error) {
                console.error(error);
                alert('Network error');
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        }
    </script>
@endsection
