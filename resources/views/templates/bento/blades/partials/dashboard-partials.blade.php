    @php
        $latestKyc = auth()->user()->kyc()->latest()->first();
        $shouldShowKycPrompt = !$latestKyc || $latestKyc->status == 'rejected' || config('app.env') == 'sandbox';
        if (!moduleEnabled('kyc_module')) {
            $shouldShowKycPrompt = false;
        }
        $shouldShowOnboardingPrompt = !auth()->user()->onboarding || config('app.env') == 'sandbox';

    @endphp

    @if ($shouldShowOnboardingPrompt)
        <div id="onboarding-prompt"
            class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 p-4 rounded-xl bg-accent-secondary/5 border border-accent-secondary/10 relative overflow-hidden transition-all duration-300">

            <div class="flex items-center gap-4 relative z-10 w-full md:w-auto">
                <div
                    class="w-10 h-10 rounded-full bg-accent-secondary/10 flex items-center justify-center shrink-0 text-accent-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm">{{ __('Complete Your Profile') }}</h3>
                    <p class="text-text-secondary text-xs max-w-xl">
                        {{ __('Personalize your investment experience to get the most out of the platform.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <button type="button" id="trigger-onboarding"
                    class="whitespace-nowrap px-4 py-2 bg-accent-secondary hover:bg-accent-secondary/90 text-white text-xs font-bold rounded-lg transition-all shadow-lg shadow-accent-secondary/25 flex items-center gap-2 cursor-pointer">
                    {{ __('Complete Setup') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </button>
                <button data-element-to-close="onboarding-prompt"
                    class="close-dashboard-notification p-2 text-text-secondary hover:text-white bg-white/5 hover:bg-white/10 rounded-lg transition-colors border border-transparent hover:border-white/10 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if ($shouldShowKycPrompt)
        <div id="kyc-prompt"
            class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 p-4 rounded-xl bg-accent-primary/5 border border-accent-primary/10 relative overflow-hidden transition-all duration-300">

            <div class="flex items-center gap-4 relative z-10 w-full md:w-auto">
                <div
                    class="w-10 h-10 rounded-full bg-accent-primary/10 flex items-center justify-center shrink-0 text-accent-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-white text-sm">{{ __('Verify Your Identity') }}</h3>
                    <p class="text-text-secondary text-xs max-w-xl">
                        {{ $latestKyc && $latestKyc->status == 'rejected'
                            ? __('Your previous verification attempt was unsuccessful. Please update your documents.')
                            : __('Complete your identity verification to unlock full platform access.') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <a href="{{ route('user.kyc') }}"
                    class="whitespace-nowrap px-4 py-2 bg-accent-primary hover:bg-accent-primary/90 text-white text-xs font-bold rounded-lg transition-all shadow-lg shadow-accent-primary/25 flex items-center gap-2">
                    {{ __('Start Verification') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
                <button data-element-to-close="kyc-prompt"
                    class="close-dashboard-notification p-2 text-text-secondary hover:text-white bg-white/5 hover:bg-white/10 rounded-lg transition-colors border border-transparent hover:border-white/10 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    @endif



    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.close-dashboard-notification').on('click', function() {
                    const targetId = $(this).data('element-to-close');
                    $('#' + targetId).addClass('hidden');
                });

                $('#trigger-onboarding').on('click', function() {
                    $('#onboarding-modal').removeClass('hidden opacity-0 pointer-events-none');
                    $('#onboarding-content').removeClass('scale-95');
                });
            });
        </script>
    @endpush
