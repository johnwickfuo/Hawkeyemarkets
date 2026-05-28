<table class="w-full text-left border-collapse min-w-[1000px]">
    <thead>
        <tr class="bg-white/[0.02]">
            <th
                class="px-6 py-4 border-b border-white/5 text-[10px] text-base font-black uppercase text-text-secondary tracking-widest">
                {{ __('Date') }}
            </th>
            <th
                class="px-6 py-4 border-b border-white/5 text-[10px] text-base font-black uppercase text-text-secondary tracking-widest">
                {{ __('User') }}
            </th>
            <th
                class="px-6 py-4 border-b border-white/5 text-[10px] text-base font-black uppercase text-text-secondary tracking-widest">
                {{ __('Plan Details') }}
            </th>

            <th
                class="px-6 py-4 border-b border-white/5 text-[10px] text-base font-black uppercase text-text-secondary tracking-widest text-right">
                {{ __('Earnings') }}
            </th>
            <th
                class="px-6 py-4 border-b border-white/5 text-[10px] text-base font-black uppercase text-text-secondary tracking-widest">
                {{ __('Note') }}
            </th>
            <th
                class="px-6 py-4 border-b border-white/5 text-[10px] text-base font-black uppercase text-text-secondary tracking-widest text-right">
                {{ __('Actions') }}
            </th>
        </tr>
    </thead>
    <tbody class="divide-y divide-white/5">
        @forelse ($earnings as $earning)
            <tr class="hover:bg-white/[0.01] transition-all group">
                <td class="px-6 py-4">
                    <span class="text-[10px] text-text-secondary block mb-1">
                        {{ $earning->created_at->format('M d, Y') }}
                    </span>
                    <span class="text-[9px] text-text-secondary/60">
                        {{ $earning->created_at->format('H:i') }}
                    </span>
                </td>

                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if ($earning->user->photo)
                            <div class="w-8 h-8 rounded-full border border-white/10 shadow-lg overflow-hidden shrink-0">
                                <img src="{{ asset('storage/profile/' . $earning->user->photo) }}"
                                    alt="{{ $earning->user->username }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div
                                class="w-8 h-8 rounded-full bg-accent-primary/10 flex items-center justify-center text-accent-primary font-black text-xs shrink-0">
                                {{ substr($earning->user->username ?? 'U', 0, 2) }}
                            </div>
                        @endif
                        <div>
                            @if ($earning->user)
                                <a href="{{ route('admin.users.detail', $earning->user->id) }}"
                                    class="text-xs text-white font-bold hover:text-accent-primary transition-colors block cursor-pointer">
                                    {{ $earning->user->username }}
                                </a>
                                <span
                                    class="text-[10px] text-text-secondary block mb-1.5">{{ $earning->user->email }}</span>
                            @else
                                <span
                                    class="text-xs text-slate-400 font-bold block mb-1.5">{{ __('Deleted User') }}</span>
                            @endif
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4">
                    @if ($earning->investment && $earning->investment->plan)
                        <span
                            class="text-xs text-white font-medium block mb-1.5">{{ $earning->investment->plan->name }}</span>

                        <div class="flex flex-wrap items-center gap-1.5 mb-1.5">
                            @php
                                $goalColor = match ($earning->investment_goal) {
                                    'short_term' => 'text-sky-400 bg-sky-400/10 border-sky-400/20',
                                    'medium_term' => 'text-indigo-400 bg-indigo-400/10 border-indigo-400/20',
                                    'long_term' => 'text-violet-400 bg-violet-400/10 border-violet-400/20',
                                    default => 'text-slate-400 bg-white/5 border-white/5',
                                };

                                $riskColor = match ($earning->risk_profile) {
                                    'conservative' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
                                    'balanced' => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
                                    'growth' => 'text-rose-400 bg-rose-400/10 border-rose-400/20',
                                    default => 'text-slate-400 bg-white/5 border-white/5',
                                };
                            @endphp

                            @if ($earning->investment_goal)
                                <span
                                    class="px-1.5 py-0.5 rounded-md text-[8px] font-bold uppercase tracking-wider border {{ $goalColor }}">
                                    {{ __($earning->investment_goal) }}
                                </span>
                            @endif

                            @if ($earning->risk_profile)
                                <span
                                    class="px-1.5 py-0.5 rounded-md text-[8px] font-bold uppercase tracking-wider border {{ $riskColor }}">
                                    {{ __($earning->risk_profile) }}
                                </span>
                            @endif
                        </div>

                        {{-- Interests --}}
                        @if ($earning->interest)
                            <div class="flex flex-wrap gap-1 max-w-[150px]">
                                @foreach ((array) $earning->interest as $int)
                                    <span
                                        class="text-[8px] px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                        {{ __($int) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <span
                            class="text-xs text-slate-400 italic block">{{ __('Investment/Plan Data Unavailable') }}</span>
                    @endif
                </td>



                <td class="px-6 py-4 text-right">
                    <span class="text-sm text-emerald-400 font-bold block">
                        +{{ showAmount($earning->amount) }}
                    </span>
                </td>

                <td class="px-6 py-4">
                    <span class="text-[10px] text-text-secondary max-w-[200px] block truncate"
                        title="{{ $earning->note }}">
                        {{ $earning->note ?: '-' }}
                    </span>
                </td>

                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2 transition-opacity">
                        <button type="button" onclick="openDeleteModal('{{ $earning->id }}')"
                            class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition-all cursor-pointer"
                            title="{{ __('Delete Record') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center border-b border-white/5">
                    <div class="flex flex-col items-center justify-center text-text-secondary">
                        <svg class="w-12 h-12 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-sm font-medium">{{ __('No earning records found.') }}</p>
                        <p class="text-xs mt-1">{{ __('Try adjusting your filters or date range.') }}</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
