@php
    $hasChildren = !empty($node['children']);
    $depth = $node['level'];
    $nodeId = 'node-' . $node['user']->id . '-' . bin2hex(random_bytes(4));
    $networkUrl = request()->fullUrlWithQuery(['user_id' => $node['user']->id]);
@endphp

<div class="relative ml-4 first:ml-0" data-depth="{{ $depth }}">
    {{-- Vertical connecting line --}}
    @if ($depth > 1)
        <div class="absolute -left-6 top-0 bottom-0 w-px bg-white/10"></div>
        <div class="absolute -left-6 top-6 w-6 h-px bg-white/10"></div>
    @endif

    <div class="relative z-10 group flex items-center gap-2">
        {{-- Expansion Toggle --}}
        @if ($hasChildren)
            <button onclick="toggleTreeNode('{{ $nodeId }}')"
                class="shrink-0 w-6 h-6 rounded-md bg-white/5 border border-white/10 flex items-center justify-center text-text-secondary hover:text-white transition-all transform hover:scale-110 active:scale-95 tree-toggle-btn"
                data-target="{{ $nodeId }}">
                <svg class="w-3 h-3 transition-transform duration-300 transform rotate-90" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        @else
            <div class="w-6 h-6 shrink-0"></div>
        @endif

        {{-- Clicking the card roots the tree to this user --}}
        <div class="w-[550px] shrink-0 flex items-start gap-3 p-3 rounded-xl bg-white/[0.01] border border-white/5 hover:bg-white/[0.04] hover:border-accent-primary/30 transition-all cursor-pointer shadow-lg"
            onclick="window.location.href='{{ $networkUrl }}'">

            {{-- Avatar/Level Badge --}}
            <div class="relative shrink-0">
                @if ($node['user']->photo)
                    <img src="{{ asset('storage/profile/' . $node['user']->photo) }}"
                        class="w-10 h-10 rounded-full border border-white/10 object-cover">
                @else
                    <div
                        class="w-10 h-10 rounded-full bg-secondary border border-white/10 text-white flex items-center justify-center font-bold text-xs uppercase">
                        {{ substr($node['user']->first_name, 0, 1) }}{{ substr($node['user']->last_name, 0, 1) }}
                    </div>
                @endif
                <div
                    class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-accent-primary text-slate-950 flex items-center justify-center font-black text-[9px] border-2 border-secondary shadow-sm">
                    L{{ $depth }}
                </div>
            </div>

            {{-- Info Content --}}
            <div class="flex-1 min-w-0 flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span
                            class="text-sm font-bold text-white group-hover:text-accent-primary transition-colors truncate">
                            {{ $node['user']->first_name }} {{ $node['user']->last_name }}
                        </span>

                        <a href="{{ route('admin.users.detail', $node['user']->id) }}"
                            onclick="event.stopPropagation();"
                            class="p-1 rounded-md hover:bg-white/10 text-text-secondary hover:text-white transition-colors tooltip-trigger shrink-0"
                            title="{{ __('View Profile') }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                </path>
                            </svg>
                        </a>
                    </div>
                    <p class="text-[9px] text-text-secondary mt-0.5 uppercase tracking-widest font-bold opacity-50">
                        {{ $node['user']->created_at->format('M d, Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-6 shrink-0">
                    {{-- Personal Stats (Compact) --}}
                    <div class="text-right">
                        <div class="space-y-0 text-[10px] font-black">
                            <p class="text-white whitespace-nowrap"><span
                                    class="opacity-40 font-bold mr-1">{{ __('DEP:') }}</span>{{ showAmount($node['personal_deposits']) }}
                            </p>
                            <p class="text-accent-primary whitespace-nowrap"><span
                                    class="opacity-40 font-bold mr-1">{{ __('PAY:') }}</span>{{ showAmount($node['personal_payouts']) }}
                            </p>
                        </div>
                    </div>
                    {{-- Network Stats (Compact) --}}
                    <div class="text-right px-4 border-l border-white/5">
                        <div class="space-y-0">
                            <p class="text-xs font-black text-white whitespace-nowrap"
                                title="{{ __('Network Deposits') }}">
                                <span
                                    class="text-[9px] opacity-40 font-bold mr-1">{{ __('NET D:') }}</span>{{ showAmount($node['network_deposits']) }}
                            </p>
                            <p class="text-xs font-black text-accent-primary whitespace-nowrap"
                                title="{{ __('Network Payouts') }}">
                                <span
                                    class="text-[9px] opacity-40 font-bold mr-1">{{ __('NET P:') }}</span>{{ showAmount($node['network_payouts']) }}
                            </p>
                        </div>
                        <p class="text-[8px] font-mono text-text-secondary mt-0.5 italic whitespace-nowrap opacity-60">
                            {{ $node['network_size'] }} {{ __('members') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recursive Children --}}
    @if ($hasChildren)
        <div id="{{ $nodeId }}"
            class="mt-2 ml-6 space-y-1.5 border-l border-white/5 pl-4 pb-2 transition-all duration-300">
            @foreach ($node['children'] as $childNode)
                @include('templates.bento.blades.admin.referral-networks.partials.tree-item', [
                    'node' => $childNode,
                ])
            @endforeach
        </div>
    @endif
</div>
