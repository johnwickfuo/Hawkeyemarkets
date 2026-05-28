@php
    $referralUser = $node['user'];
    $level = $node['level'];
    $children = $node['children'];
    $hasChildren = count($children) > 0;
    $nodeId = 'node-' . $referralUser->id;
@endphp

<div class="tree-node group">
    <div
        class="flex items-center gap-4 p-4 bg-white/5 border border-white/5 rounded-2xl hover:bg-white/[0.08] transition-all duration-300">
        {{-- Ava/Icon --}}
        <div
            class="w-10 h-10 rounded-xl bg-secondary flex items-center justify-center text-text-secondary border border-white/10 group-hover:border-accent-primary/20 transition-all">
            <svg class="w-5 h-5 opacity-40 group-hover:text-accent-primary group-hover:opacity-100 transition-all"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>

        {{-- Info --}}
        <div class="flex-1">
            <div class="flex items-center gap-2">
                <span class="text-white font-bold text-sm tracking-tight">{{ $referralUser->first_name }}
                    {{ $referralUser->last_name }}</span>
                <span
                    class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-widest bg-accent-primary/10 text-accent-primary border border-accent-primary/20">
                    {{ __('LVL') }} {{ $level }}
                </span>
            </div>
            @php
                $emailParts = explode('@', $referralUser->email);
                $maskedEmail = substr($emailParts[0], 0, 2) . '***@' . ($emailParts[1] ?? '');
            @endphp
            <p class="text-text-secondary text-[10px] opacity-40 font-mono">{{ $maskedEmail }}</p>
        </div>

        {{-- Referral Code --}}
        <div class="text-right hidden sm:block mr-4">
            <p class="text-[8px] font-black text-text-secondary uppercase tracking-widest opacity-40">
                {{ __('Ref Code') }}</p>
            <p class="text-white font-mono text-[10px] font-bold bg-white/5 px-2 py-0.5 rounded border border-white/5">
                {{ $referralUser->referral_code }}</p>
        </div>

        {{-- Toggler --}}
        @if ($hasChildren)
            <button type="button" data-target="{{ $nodeId }}"
                class="tree-toggler w-8 h-8 rounded-lg bg-white/5 hover:bg-accent-primary hover:text-white flex items-center justify-center text-text-secondary transition-all cursor-pointer">
                <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        @else
            <div class="w-8 h-8"></div>
        @endif
    </div>

    {{-- Children Container --}}
    @if ($hasChildren)
        <div id="{{ $nodeId }}" class="ml-8 mt-4 space-y-4 border-l-2 border-white/5 pl-8 hidden">
            @foreach ($children as $child)
                @include('templates.bento.blades.partials.referral-tree-item', ['node' => $child])
            @endforeach
        </div>
    @endif
</div>
