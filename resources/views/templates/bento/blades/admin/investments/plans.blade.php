@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div id="plans-wrapper" class="space-y-8">

        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('investment_module'))
            <div
                class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px] bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                    {{ __('Investment module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('Investment module is disabled. Please enable the investment module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        @if (moduleEnabled('investment_module'))
            {{-- Page Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ __('Investment Plans') }}</h1>
                    <p class="text-sm text-text-secondary mt-1">
                        {{ __('Manage, create, and configure the investment plans offered to your users.') }}
                    </p>
                </div>
                <a href="{{ route('admin.investments.plans.create') }}"
                    class="flex items-center gap-2 bg-accent-primary text-white px-5 py-2.5 rounded-xl font-bold hover:bg-accent-primary/90 transition-all shadow-lg shadow-accent-primary/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ __('Create New Plan') }}
                </a>
            </div>

            {{-- Analytics Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Plans --}}
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-blue-500/20 rounded-lg text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Total Plans') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_plans']) }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Active Plans --}}
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-emerald-500/20 rounded-lg text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Active Plans') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($stats['active_plans']) }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Total Active Investments --}}
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Active Investments') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ number_format($stats['total_investments']) }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Total Invested --}}
                <div
                    class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="p-3 bg-amber-500/20 rounded-lg text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                                {{ __('Total Invested') }}</p>
                            <h4 class="text-xl font-bold text-white">{{ showAmount($stats['total_invested']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Plans Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($plans as $plan)
                    <div
                        class="bg-secondary relative border border-white/5 rounded-2xl overflow-hidden hover:border-white/10 transition-colors flex flex-col group">
                        {{-- Featured Glow Effect --}}
                        @if ($plan->is_featured)
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent pointer-events-none z-0">
                            </div>
                            <div
                                class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-amber-500/10 rounded-full blur-3xl pointer-events-none z-0">
                            </div>
                        @endif

                        {{-- Top Banner / Header --}}
                        <div class="relative z-10 p-6 border-b border-white/5 flex flex-col items-start gap-4">
                            <div class="w-full flex items-start justify-between gap-3">
                                <h3 class="text-xl font-bold text-white leading-tight">
                                    {{ $plan->name }}
                                </h3>
                                <div class="flex items-center gap-2">
                                    @if ($plan->is_featured)
                                        <span
                                            class="px-2 py-1 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-500 text-[9px] font-black uppercase tracking-widest flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z">
                                                </path>
                                            </svg>
                                            {{ __('Featured') }}
                                        </span>
                                    @endif
                                    <span
                                        class="px-2 py-1 rounded-md text-[9px] font-bold uppercase tracking-widest border {{ $plan->is_enabled ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : 'bg-red-500/10 border-red-500/20 text-red-500' }}">
                                        {{ $plan->is_enabled ? __('Active') : __('Disabled') }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-sm text-text-secondary line-clamp-2">
                                {{ $plan->description ?: __('No description provided.') }}
                            </p>
                        </div>

                        {{-- Metrics Grid --}}
                        <div class="relative z-10 p-6 grid grid-cols-2 gap-4 bg-white/[0.01]">
                            <div>
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">
                                    {{ __('Return (ROI)') }}</p>
                                <p class="text-lg font-bold text-emerald-400">
                                    {{ rtrim(rtrim((string) $plan->return_percent, '0'), '.') }}%</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">
                                    {{ __('Duration') }}</p>
                                <p class="text-lg font-bold text-white">{{ $plan->duration }} {{ __('Days') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">
                                    {{ __('Min Investment') }}</p>
                                <p class="font-medium text-white">{{ showAmount($plan->min_investment) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-text-secondary uppercase tracking-widest font-bold mb-1">
                                    {{ __('Max Investment') }}</p>
                                <p class="font-medium text-white">{{ showAmount($plan->max_investment) }}</p>
                            </div>
                        </div>

                        {{-- Features & Labels --}}
                        <div class="relative z-10 p-6 border-t border-white/5 flex-1 flex flex-col justify-start">

                            {{-- Tags / Interests --}}
                            <div class="flex flex-wrap gap-1.5 mb-5">
                                @if ($plan->risk_profile)
                                    @php
                                        $riskColor = match ($plan->risk_profile) {
                                            'conservative'
                                                => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                            'balanced' => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                                            'growth' => 'text-rose-400 bg-rose-400/10 border-rose-400/20',
                                            default => 'text-slate-400 bg-white/5 border-white/5',
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-0.5 rounded border text-[9px] uppercase tracking-wider font-bold {{ $riskColor }}">
                                        {{ __($plan->risk_profile) }}
                                    </span>
                                @endif

                                @if ($plan->investment_goal)
                                    @php
                                        $goalColor = match ($plan->investment_goal) {
                                            'short_term' => 'text-sky-400 bg-sky-400/10 border-sky-400/20',
                                            'medium_term' => 'text-indigo-400 bg-indigo-400/10 border-indigo-400/20',
                                            'long_term' => 'text-violet-400 bg-violet-400/10 border-violet-400/20',
                                            default => 'text-slate-400 bg-white/5 border-white/5',
                                        };
                                    @endphp
                                    <span
                                        class="px-2 py-0.5 rounded border text-[9px] uppercase tracking-wider font-bold {{ $goalColor }}">
                                        {{ __($plan->investment_goal) }}
                                    </span>
                                @endif

                                @if ($plan->interests && is_array($plan->interests))
                                    @foreach ($plan->interests as $interest)
                                        <span
                                            class="px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[9px] uppercase tracking-wider font-bold">
                                            {{ __($interest) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>

                            {{-- Properties List --}}
                            <ul class="space-y-3 mt-auto">
                                <li class="flex items-center justify-between text-sm">
                                    <span class="text-text-secondary">{{ __('Compounding') }}</span>
                                    @if ($plan->compounding)
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                </li>
                                <li class="flex items-center justify-between text-sm">
                                    <span class="text-text-secondary">{{ __('Capital Returned') }}</span>
                                    @if ($plan->capital_returned)
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                </li>
                                <li class="flex items-center justify-between text-sm">
                                    <span class="text-text-secondary">{{ __('Return Interval') }}</span>
                                    <span class="text-white font-bold">{{ __($plan->return_interval) }}</span>
                                </li>
                                <li class="flex items-center justify-between text-sm py-2 border-t border-white/5 mt-2">
                                    <span class="text-text-secondary">{{ __('Active Investments') }}</span>
                                    <span class="text-white font-bold">{{ $plan->investments_count }}</span>
                                </li>
                            </ul>
                        </div>

                        {{-- Actions Footer --}}
                        <div
                            class="relative z-10 p-4 border-t border-white/5 bg-secondary flex justify-between gap-3 mt-auto transition-colors group-hover:bg-white/[0.02]">
                            <a href="{{ route('admin.investments.plans.edit', $plan->id) }}"
                                class="flex-1 py-2 text-sm text-white font-medium bg-white/5 rounded-lg hover:bg-white/10 hover:text-accent-primary transition-all flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                {{ __('Edit') }}
                            </a>
                            <button type="button"
                                onclick="openDeletePlanModal('{{ $plan->id }}', '{{ route('admin.investments.plans.delete', $plan->id) }}')"
                                class="flex-1 py-2 text-sm text-red-500 font-medium bg-red-500/10 rounded-lg hover:bg-red-500 hover:text-white transition-all flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-16 flex flex-col items-center justify-center bg-secondary border border-white/5 rounded-2xl text-center">
                        <div
                            class="w-16 h-16 bg-accent-primary/10 text-accent-primary rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">{{ __('No Investment Plans Found') }}</h3>
                        <p class="text-text-secondary max-w-md mx-auto mb-6">
                            {{ __('You have not created any investment plans yet. Create your first plan to allow users to start investing.') }}
                        </p>
                        <a href="{{ route('admin.investments.plans.create') }}"
                            class="bg-accent-primary text-white px-6 py-2.5 rounded-xl font-bold hover:bg-accent-primary/90 transition-all">
                            {{ __('Create Your First Plan') }}
                        </a>
                    </div>
                @endforelse
            </div>
        @endif

    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal"
        class="hidden fixed inset-0 bg-secondary/90 backdrop-blur-sm z-[100] flex items-center justify-center p-4 transition-all duration-300">
        <div id="deleteModal-content"
            class="bg-secondary-dark border border-white/10 w-full max-w-md rounded-2xl shadow-2xl scale-95 opacity-0 transition-all duration-300 relative overflow-hidden">
            <div
                class="absolute inset-0 bg-[url('{{ asset('/assets/images/noise.svg') }}')] opacity-[0.03] pointer-events-none">
            </div>
            <div
                class="absolute top-0 right-0 -mt-16 -mr-16 w-32 h-32 bg-red-500/10 rounded-full blur-2xl pointer-events-none z-0">
            </div>

            <div class="p-6 relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-white flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center text-red-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        {{ __('Confirm Deletion') }}
                    </h3>
                    <button type="button" onclick="closeModal('deleteModal')"
                        class="text-text-secondary hover:text-white transition-colors cursor-pointer modal-close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="mb-8">
                    <p class="text-text-secondary mb-3">
                        {{ __('Are you sure you want to delete this investment plan? This action cannot be undone.') }}</p>
                    <p class="text-red-400 text-sm font-medium bg-red-500/10 p-3 rounded-lg border border-red-500/20">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('WARNING: Deleting this plan will also automatically delete all active investments currently utilizing it. Proceed with caution.') }}
                    </p>
                </div>

                <input type="hidden" id="delete-plan-id">
                <input type="hidden" id="delete-plan-url">

                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('deleteModal')"
                        class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all cursor-pointer modal-close">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" id="confirm-delete-btn"
                        class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-bold hover:bg-red-600 shadow-lg shadow-red-500/20 transition-all cursor-pointer relative overflow-hidden group">
                        <span class="relative z-10">{{ __('Yes, Delete It') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        window.openDeletePlanModal = function(id, url) {
            $('#delete-plan-id').val(id);
            $('#delete-plan-url').val(url);
            const $modal = $('#deleteModal');
            const $content = $('#deleteModal-content');
            $modal.removeClass('hidden');
            setTimeout(() => {
                $content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 10);
        };

        window.closeModal = function(modalId) {
            const $modal = $('#' + modalId);
            const $content = $('#' + modalId + '-content');
            $content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(() => {
                $modal.addClass('hidden');
            }, 300);
        };

        $(document).on('click', '.modal-close', function(e) {
            if (e.target === this || $(this).hasClass('modal-close')) {
                const modal = $(this).closest('.fixed.inset-0');
                if (modal.length && modal.attr('id') === 'deleteModal') {
                    closeModal('deleteModal');
                }
            }
        });

        $('#confirm-delete-btn').on('click', function() {
            const url = $('#delete-plan-url').val(); // Get the URL from the hidden input
            const $btn = $(this);
            const originalText = $btn.html();

            $btn.html(
                '<svg class="w-5 h-5 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>'
            );
            $btn.prop('disabled', true);

            $.ajax({
                url: url, // Use the URL from the hidden input
                type: 'POST', // Use POST method

                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success || response.status === 'success') {
                        toastNotification(response.message, 'success');
                        closeModal('deleteModal');

                        // ajax update the page
                        $.ajax({
                            url: window.location.href,
                            type: 'GET',
                            success: function(html) {
                                $('#plans-wrapper').html($(html).find('#plans-wrapper')
                                    .html());
                                $btn.html(originalText).prop('disabled', false);
                            }
                        });
                    } else {
                        toastNotification(response.message || 'Error deleting plan.', 'error');
                        $btn.html(originalText).prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message ||
                        '{{ __('An error occurred during deletion.') }}';
                    toastNotification(msg, 'error');
                    $btn.html(originalText).prop('disabled', false);
                }
            });
        });
    </script>
@endpush
