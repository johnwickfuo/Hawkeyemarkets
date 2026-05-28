<!-- Onboarding Modal Overlay -->
<div id="onboarding-modal"
    class="fixed inset-0 bg-[#0f172a]/90 backdrop-blur-xl z-[60] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-500">

    <!-- Modal Container -->
    <div class="relative w-full max-w-2xl bg-[#1e293b] border border-white/5 rounded-3xl shadow-2xl overflow-hidden transform scale-95 transition-all duration-500 group"
        id="onboarding-content">

        <!-- Decoration Gradient -->
        <div
            class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-accent-primary/10 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-accent-secondary/10 rounded-full blur-3xl opacity-50 pointer-events-none">
        </div>

        <!-- Header -->
        <div class="relative px-6 md:px-8 pt-8 pb-4 flex items-start justify-between z-10">
            <div>
                <h2 class="text-xl md:text-2xl font-heading font-bold text-white tracking-tight">
                    {{ __('Welcome to :site_name', ['site_name' => getSetting('name')]) }}</h2>
                <p class="text-xs md:text-sm text-text-secondary mt-2 leading-relaxed max-w-md">
                    {{ __('Let\'s personalize your investment experience.') }}</p>
            </div>

            <button type="button" id="onboarding-close"
                class="p-2 -mr-2 text-text-secondary hover:text-white bg-white/5 hover:bg-white/10 rounded-full transition-colors cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <!-- Progress Bar -->
        <div class="px-6 md:px-8 mt-2 mb-6">
            <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                <div id="onboarding-progress"
                    class="h-full bg-gradient-to-r from-accent-primary to-accent-secondary w-1/2 transition-all duration-500 ease-out">
                </div>
            </div>
            <div class="flex justify-between mt-2 text-[10px] font-bold uppercase tracking-widest text-text-secondary">
                <span class="transition-colors duration-300 text-accent-primary"
                    id="step-label-1">{{ __('Risk Profile') }}</span>
                <span class="transition-colors duration-300" id="step-label-2">{{ __('Investment Goal') }}</span>
            </div>
        </div>

        <!-- Form Container -->
        <form id="onboarding-form" action="{{ route('user.onboarding') }}" method="POST"
            class="relative z-10 flex flex-col h-full">
            @csrf

            <!-- Step 1: Risk Profile -->
            <div class="onboarding-step px-6 md:px-8 pb-8" data-step="1">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">

                    <!-- Conservative -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="risk_profile" value="conservative" class="peer sr-only">
                        <div
                            class="h-full p-4 md:p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] 
                                    peer-checked:bg-gradient-to-r md:peer-checked:bg-gradient-to-b peer-checked:from-emerald-500/10 peer-checked:to-transparent 
                                    peer-checked:border-emerald-500/50 peer-checked:ring-1 peer-checked:ring-emerald-500/50 
                                    transition-all duration-300 flex flex-row md:flex-col items-center md:items-center text-left md:text-center gap-4">

                            <div
                                class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 text-emerald-400 flex items-center justify-center shadow-lg shadow-emerald-900/20 group-hover:scale-110 transition-transform duration-300 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 md:w-7 md:h-7">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-base md:text-lg font-bold text-white group-hover:text-emerald-400 transition-colors">
                                    {{ __('Conservative') }}</h4>
                                <p class="text-xs text-text-secondary mt-1 md:mt-2 leading-relaxed opacity-80">
                                    {{ __('Low risk, steady returns. Prioritizes safety.') }}</p>
                            </div>
                            <div
                                class="ml-auto md:ml-0 md:mt-auto md:pt-4 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-emerald-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 md:w-6 md:h-6">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Balanced -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="risk_profile" value="balanced" class="peer sr-only">
                        <div
                            class="h-full p-4 md:p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] 
                                    peer-checked:bg-gradient-to-r md:peer-checked:bg-gradient-to-b peer-checked:from-blue-500/10 peer-checked:to-transparent 
                                    peer-checked:border-blue-500/50 peer-checked:ring-1 peer-checked:ring-blue-500/50 
                                    transition-all duration-300 flex flex-row md:flex-col items-center md:items-center text-left md:text-center gap-4">

                            <div
                                class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-blue-500/20 to-blue-500/5 text-blue-400 flex items-center justify-center shadow-lg shadow-blue-900/20 group-hover:scale-110 transition-transform duration-300 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 md:w-7 md:h-7">
                                    <path d="M6 3v18" />
                                    <path d="M18 3v18" />
                                    <path d="M12 7v14" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-base md:text-lg font-bold text-white group-hover:text-blue-400 transition-colors">
                                    {{ __('Balanced') }}</h4>
                                <p class="text-xs text-text-secondary mt-1 md:mt-2 leading-relaxed opacity-80">
                                    {{ __('Moderate risk, balanced growth potential.') }}</p>
                            </div>
                            <div
                                class="ml-auto md:ml-0 md:mt-auto md:pt-4 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 md:w-6 md:h-6">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Aggressive (Mapped to 'growth' for backend) -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="risk_profile" value="growth" class="peer sr-only">
                        <div
                            class="h-full p-4 md:p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] 
                                    peer-checked:bg-gradient-to-r md:peer-checked:bg-gradient-to-b peer-checked:from-red-500/10 peer-checked:to-transparent 
                                    peer-checked:border-red-500/50 peer-checked:ring-1 peer-checked:ring-red-500/50 
                                    transition-all duration-300 flex flex-row md:flex-col items-center md:items-center text-left md:text-center gap-4">

                            <div
                                class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-red-500/20 to-red-500/5 text-red-400 flex items-center justify-center shadow-lg shadow-red-900/20 group-hover:scale-110 transition-transform duration-300 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 md:w-7 md:h-7">
                                    <path d="M12 20V10" />
                                    <path d="M18 20V4" />
                                    <path d="M6 20v-4" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-base md:text-lg font-bold text-white group-hover:text-red-400 transition-colors">
                                    {{ __('Aggressive') }}</h4>
                                <p class="text-xs text-text-secondary mt-1 md:mt-2 leading-relaxed opacity-80">
                                    {{ __('High risk, maximum growth targeting.') }}</p>
                            </div>
                            <div
                                class="ml-auto md:ml-0 md:mt-auto md:pt-4 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 md:w-6 md:h-6">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Step 2: Investment Goal -->
            <div class="onboarding-step hidden px-6 md:px-8 pb-8" data-step="2">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">

                    <!-- Grow Wealth (Long Term) -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="investment_goal" value="long_term" class="peer sr-only">
                        <div
                            class="h-full p-4 md:p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] 
                                    peer-checked:bg-gradient-to-r md:peer-checked:bg-gradient-to-b peer-checked:from-violet-500/10 peer-checked:to-transparent 
                                    peer-checked:border-violet-500/50 peer-checked:ring-1 peer-checked:ring-violet-500/50 
                                    transition-all duration-300 flex flex-row md:flex-col items-center md:items-center text-left md:text-center gap-4">

                            <div
                                class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-violet-500/20 to-violet-500/5 text-violet-400 flex items-center justify-center shadow-lg shadow-violet-900/20 group-hover:scale-110 transition-transform duration-300 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 md:w-7 md:h-7">
                                    <path d="M12 2v20" />
                                    <path d="m17 5-5-3-5 3" />
                                    <path d="m17 19-5 3-5-3" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-base md:text-lg font-bold text-white group-hover:text-violet-400 transition-colors">
                                    {{ __('Grow Wealth') }}</h4>
                                <p class="text-xs text-text-secondary mt-1 md:mt-2 leading-relaxed opacity-80">
                                    {{ __('Long-term capital appreciation.') }}</p>
                            </div>
                            <div
                                class="ml-auto md:ml-0 md:mt-auto md:pt-4 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-violet-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 md:w-6 md:h-6">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Monthly Income (Medium Term) -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="investment_goal" value="medium_term" class="peer sr-only">
                        <div
                            class="h-full p-4 md:p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] 
                                    peer-checked:bg-gradient-to-r md:peer-checked:bg-gradient-to-b peer-checked:from-emerald-500/10 peer-checked:to-transparent 
                                    peer-checked:border-emerald-500/50 peer-checked:ring-1 peer-checked:ring-emerald-500/50 
                                    transition-all duration-300 flex flex-row md:flex-col items-center md:items-center text-left md:text-center gap-4">

                            <div
                                class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-500/5 text-emerald-400 flex items-center justify-center shadow-lg shadow-emerald-900/20 group-hover:scale-110 transition-transform duration-300 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 md:w-7 md:h-7">
                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                    <line x1="2" x2="22" y1="10" y2="10" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-base md:text-lg font-bold text-white group-hover:text-emerald-400 transition-colors">
                                    {{ __('Monthly Income') }}</h4>
                                <p class="text-xs text-text-secondary mt-1 md:mt-2 leading-relaxed opacity-80">
                                    {{ __('Regular payouts and dividends.') }}</p>
                            </div>
                            <div
                                class="ml-auto md:ml-0 md:mt-auto md:pt-4 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-emerald-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 md:w-6 md:h-6">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Capital Preservation (Short Term) -->
                    <label class="cursor-pointer group relative">
                        <input type="radio" name="investment_goal" value="short_term" class="peer sr-only">
                        <div
                            class="h-full p-4 md:p-6 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] 
                                    peer-checked:bg-gradient-to-r md:peer-checked:bg-gradient-to-b peer-checked:from-amber-500/10 peer-checked:to-transparent 
                                    peer-checked:border-amber-500/50 peer-checked:ring-1 peer-checked:ring-amber-500/50 
                                    transition-all duration-300 flex flex-row md:flex-col items-center md:items-center text-left md:text-center gap-4">

                            <div
                                class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-500/5 text-amber-400 flex items-center justify-center shadow-lg shadow-amber-900/20 group-hover:scale-110 transition-transform duration-300 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 md:w-7 md:h-7">
                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                    <path d="M12 8v8" />
                                    <path d="M8 12h8" />
                                </svg>
                            </div>
                            <div>
                                <h4
                                    class="text-base md:text-lg font-bold text-white group-hover:text-amber-400 transition-colors">
                                    {{ __('Capital Preservation') }}</h4>
                                <p class="text-xs text-text-secondary mt-1 md:mt-2 leading-relaxed opacity-80">
                                    {{ __('Protect principal with low risk.') }}</p>
                            </div>
                            <div
                                class="ml-auto md:ml-0 md:mt-auto md:pt-4 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100 text-amber-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 md:w-6 md:h-6">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 md:px-8 pb-8 pt-2 flex items-center justify-between">
                <button type="button" id="onboarding-back"
                    class="hidden text-sm font-medium md:font-bold text-text-secondary hover:text-white transition-colors px-4 md:px-6 py-3 hover:bg-white/5 rounded-xl border border-transparent hover:border-white/10 cursor-pointer">
                    {{ __('Back') }}
                </button>
                <div class="ml-auto w-full md:w-auto">
                    <button type="button" id="onboarding-next" disabled
                        class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-accent-primary to-accent-glow text-white font-bold text-sm shadow-xl shadow-accent-primary/20 hover:shadow-accent-primary/40 disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                        <span>{{ __('Next Step') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 2;

            function checkOnboardingThrottle() {
                if ("{{ config('app.env') }}" !== 'sandbox') return true;

                const lastSubmitted = localStorage.getItem('sandbox_onboarding_submitted');
                if (!lastSubmitted) return true;

                const now = new Date().getTime();
                const twentyFourHours = 24 * 60 * 60 * 1000;

                if (now - parseInt(lastSubmitted) > twentyFourHours) {
                    localStorage.removeItem('sandbox_onboarding_submitted');
                    return true;
                }

                return false;
            }

            // Open Modal (Function to be called externally if needed, currently auto-trigger for demo)
            // Backend controls if this partial is rendered, so we just show it.
            // For demo, we'll fade it in
            if (checkOnboardingThrottle()) {
                setTimeout(() => {
                    $('#onboarding-modal').removeClass('pointer-events-none opacity-0');
                    $('#onboarding-content').removeClass('scale-95');
                }, 500); // Slight delay for smoother entrance
            }

            // Close Modal Button
            $('#onboarding-close').click(function() {
                $('#onboarding-modal').addClass('opacity-0 pointer-events-none');
                $('#onboarding-content').addClass('scale-95');
            });

            // Handle Selection Highlight & Next Button Enable state
            $('input[type="radio"]').change(function() {
                // This logic is simplified as the new design handles selection highlight via peer-checked
                // We only need to enable the next button if a selection is made
                $('#onboarding-next').prop('disabled', false);
            });

            // Next Button Click
            $('#onboarding-next').click(function() {
                if (currentStep < totalSteps) {

                    // Fade out current step
                    $(`.onboarding-step[data-step="${currentStep}"]`).fadeOut(200, function() {
                        $(this).addClass('hidden');

                        currentStep++;

                        // Fade in next step
                        $(`.onboarding-step[data-step="${currentStep}"]`).removeClass('hidden')
                            .hide().fadeIn(300);

                        // Update Progress Bar
                        const progress = (currentStep / totalSteps) *
                            100; // Simplified for 2 steps (50% -> 100%)
                        $('#onboarding-progress').css('width', `${progress}%`);

                        // Update Text Colors
                        $('#step-label-' + (currentStep - 1)).removeClass('text-accent-primary');
                        $('#step-label-' + currentStep).addClass('text-accent-primary');

                        // Update Buttons
                        $('#onboarding-back').removeClass('hidden');
                        if (currentStep === totalSteps) {
                            $('#onboarding-next span').text('{{ __('Finish Setup') }}');
                            $('#onboarding-next svg').replaceWith(
                                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>'
                            );
                        }

                        // Disable next button until selection made for new step (unless already selected)
                        const hasSelection = $(
                                `.onboarding-step[data-step="${currentStep}"] input:checked`)
                            .length > 0;
                        $('#onboarding-next').prop('disabled', !hasSelection);
                    });

                } else {
                    // Submit Form (Finish)
                    submitOnboarding();
                }
            });

            // Back Button Click
            $('#onboarding-back').click(function() {
                if (currentStep > 1) {

                    // Fade out current step
                    $(`.onboarding-step[data-step="${currentStep}"]`).fadeOut(200, function() {
                        $(this).addClass('hidden');

                        currentStep--;

                        // Fade in previous step
                        $(`.onboarding-step[data-step="${currentStep}"]`).removeClass('hidden')
                            .hide().fadeIn(300);

                        // Update Progress Bar
                        const progress = 50; // Back to 50%
                        $('#onboarding-progress').css('width', `${progress}%`);

                        // Update Text Colors
                        $('#step-label-' + (currentStep + 1)).removeClass('text-accent-primary');
                        $('#step-label-' + currentStep).addClass('text-accent-primary');

                        // Update Buttons
                        if (currentStep === 1) {
                            $('#onboarding-back').addClass('hidden');
                        }
                        $('#onboarding-next span').text('{{ __('Next Step') }}');
                        $('#onboarding-next svg').replaceWith(
                            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>'
                        );
                        $('#onboarding-next').prop('disabled',
                            false); // Previous step must have had selection
                    });
                }
            });

            function submitOnboarding() {
                const formData = $('#onboarding-form').serialize();
                const submitBtn = $('#onboarding-next');

                // Loading State
                const originalBtnText = submitBtn.html();
                submitBtn.prop('disabled', true).html('<span class="animate-pulse">Saving...</span>');

                $.post("{{ route('user.onboarding') }}", formData)
                    .done(function(response) {
                        if ("{{ config('app.env') }}" === 'sandbox') {
                            localStorage.setItem('sandbox_onboarding_submitted', new Date().getTime());
                        }
                        if (response.status === 'success') {
                            // Success Action - Close Modal
                            $('#onboarding-modal').addClass('opacity-0 pointer-events-none');
                            $('#onboarding-content').addClass('scale-95');
                            $('#onboarding-prompt').addClass('hidden');

                            // Use global toast notification
                            window.toastNotification(response.message || '{{ __('Profile Setup Complete!') }}',
                                'success');
                        } else {
                            // Handle unexpected 200 responses that aren't success
                            window.toastNotification(response.message || '{{ __('Something went wrong.') }}',
                                'error');
                            submitBtn.prop('disabled', false).html(originalBtnText);
                        }
                    })
                    .fail(function(xhr) {
                        if ("{{ config('app.env') }}" === 'sandbox') {
                            localStorage.setItem('sandbox_onboarding_submitted', new Date().getTime());
                            // close
                            $('#onboarding-modal').addClass('opacity-0 pointer-events-none');
                            $('#onboarding-content').addClass('scale-95');
                            $('#onboarding-prompt').addClass('hidden');
                        }
                        // Error Action
                        let errorMsg = '{{ __('Something went wrong. Please try again.') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        window.toastNotification(errorMsg, 'error');

                        // Reset button
                        submitBtn.prop('disabled', false).html(originalBtnText);
                    });
            }
        });
    </script>
@endpush
