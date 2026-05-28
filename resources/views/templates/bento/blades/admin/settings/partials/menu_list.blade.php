<div class="menu-list-sortable">
    @foreach ($menus as $menu)
        <div class="menu-item-wrapper relative @if (!shouldShowLabel($menu->label)) opacity-50 grayscale @endif"
            data-id="{{ $menu->id }}">
            @if (!shouldShowLabel($menu->label))
                <div
                    class="absolute inset-0 z-20 flex items-center justify-center bg-black/60 backdrop-blur-[2px] transition-all group-hover/row:bg-black/40">
                    <a href="{{ route('admin.settings.modules.index') }}"
                        class="px-5 py-3 bg-accent-primary hover:bg-accent-secondary border border-white/20 rounded-2xl text-white shadow-[0_0_20px_rgba(var(--accent-primary-rgb),0.3)] hover:scale-105 transition-all flex items-center gap-3 active:scale-95 group">
                        <div class="flex flex-col items-start gap-0.5">
                            <span
                                class="text-[10px] font-black uppercase tracking-[0.2em] leading-none">{{ __('Module Required') }}</span>
                            <span
                                class="text-[9px] font-medium text-white/70 leading-none lowercase">{{ __('Click to enable') }}
                                {{ $menu->label }}</span>
                        </div>
                        <svg class="w-4 h-4 opacity-70 group-hover:translate-x-0.5 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            @endif
            <div class="menu-item-row group/row relative @if (!shouldShowLabel($menu->label)) pointer-events-none @endif">
                <div class="flex items-center gap-4">
                    <div
                        class="drag-handle cursor-move text-slate-500 hover:text-accent-primary transition-colors p-2 -ml-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                        </svg>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400">
                        {!! $menu->icon ??
                            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>' !!}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-white">{{ $menu->label }}</span>
                        <span class="text-[10px] text-accent-primary/60 tracking-tighter">
                            {{ $menu->route_name ?? $menu->url }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    {{-- Visibility Toggle --}}
                    <div class="flex flex-col items-end gap-1">
                        <label class="switch scale-90">
                            <input type="checkbox" onchange="toggleMenuVisibility({{ $menu->id }}, this)"
                                {{ $menu->is_active ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>

                    {{-- Dynamic Sub-item Toggle --}}
                    @if ($menu->children->count() > 0)
                        <div class="w-px h-8 bg-white/5"></div>
                        <button type="button" onclick="toggleChildren({{ $menu->id }})"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-all">
                            <svg id="chevron-{{ $menu->id }}" class="w-4 h-4 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                            <span
                                class="text-[10px] font-bold uppercase tracking-widest">{{ $menu->children->count() }}</span>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Render Children --}}
            @if ($menu->children->count() > 0)
                <div id="children-{{ $menu->id }}"
                    class="children-sortable hidden flex-col border-t border-white/5 bg-white/[0.01]">
                    @foreach ($menu->children as $child)
                        <div class="child-item bg-transparent group/child border-b border-white/[0.02] last:border-b-0 relative @if (!shouldShowLabel($child->label)) opacity-50 grayscale @endif"
                            data-id="{{ $child->id }}">
                            @if (!shouldShowLabel($child->label))
                                <div
                                    class="absolute inset-0 z-20 flex items-center justify-center bg-black/40 backdrop-blur-[1px] transition-all group-hover/child:bg-black/20">
                                    <a href="{{ route('admin.settings.modules.index') }}"
                                        class="px-4 py-2 bg-accent-primary hover:bg-accent-secondary border border-white/20 rounded-xl text-white shadow-xl hover:scale-105 transition-all flex items-center gap-2 active:scale-95 group/btn">
                                        <span
                                            class="text-[9px] font-black uppercase tracking-widest">{{ __('Enable Module') }}</span>
                                        <svg class="w-3 h-3 opacity-70 group-hover/btn:translate-x-0.5 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                            <div
                                class="menu-item-row pl-16 @if (!shouldShowLabel($child->label)) pointer-events-none @endif">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="drag-handle-child cursor-move text-slate-500 hover:text-accent-primary transition-colors p-2 -ml-6">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 8h16M4 16h16" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-white/80">{{ $child->label }}</span>
                                        <span class="text-[10px] text-accent-primary/50 tracking-tighter">
                                            {{ $child->route_name ?? $child->url }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-8">
                                    <div class="flex flex-col items-end gap-1">
                                        <label class="switch scale-75">
                                            <input type="checkbox"
                                                onchange="toggleMenuVisibility({{ $child->id }}, this)"
                                                {{ $child->is_active ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
