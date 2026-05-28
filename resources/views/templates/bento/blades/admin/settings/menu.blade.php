@php
    function shouldShowLabel($menu_label)
    {
        $menu_label = strtolower($menu_label);
        //replace_space with underscore
        $menu_label = str_replace(' ', '_', $menu_label);
        //move record
        $module_key = str_replace('_record', '', $menu_label);
        //remove 'trading'
        $module_key = str_replace('_trading', '', $module_key);

        //remove 'managed_'
        $module_key = str_replace('managed_', '', $module_key);

        $alloed_s = ['bonds', 'futures'];
        if (str_ends_with($module_key, 's') && !in_array($module_key, $alloed_s)) {
            $module_key = substr($module_key, 0, -1);
        }

        //manual matching
        $should_show = true;
        switch ($menu_label) {
            case 'capital_instruments':
                //check if the bonds, stock, and etf modules are enabled
                if (!moduleEnabled('bonds_module') && !moduleEnabled('stock_module') && !moduleEnabled('etf_module')) {
                    $should_show = false;
                }
                break;

            case 'self_trading':
                //check if futures, forex and margin are enabled
                if (
                    !moduleEnabled('futures_module') &&
                    !moduleEnabled('forex_module') &&
                    !moduleEnabled('margin_module')
                ) {
                    $should_show = false;
                }
                break;

            default:
                $module_key = $module_key . '_module';
                if (moduleExists($module_key)) {
                    $should_show = moduleEnabled($module_key);
                }
                break;
        }
        return $should_show;
    }
@endphp

@extends('templates.bento.blades.admin.layouts.admin')

@push('css')
    <style>
        /* Flat Design Adjustments */
        .settings-section {
            padding-bottom: 2rem;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .settings-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .flat-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 700;
            transition: all 0.3s ease;
            outline: none;
            width: 100%;
        }

        select.flat-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.5rem center;
            background-size: 1rem;
            padding-right: 3.5rem;
        }

        .flat-input:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(var(--accent-primary-rgb), 0.5);
        }

        .flat-input option {
            background: #0a0a0b;
            color: white;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        input:checked+.slider {
            background-color: #10b981;
            border-color: #10b981;
        }

        input:checked+.slider:before {
            transform: translateX(24px);
            background-color: white;
        }

        /* Menu Item List */
        .menu-list-sortable {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            /* Increased gap between parents */
        }

        .menu-item-wrapper {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .menu-item-wrapper:hover {
            border-color: rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.03);
        }

        .menu-item-row {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .child-item {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .child-item {
            margin-left: 2.5rem;
            position: relative;
        }

        .child-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: -1rem;
            bottom: 50%;
            width: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .child-item::after {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 50%;
            width: 1rem;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Tabs */
        .tab-btn {
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: var(--accent-primary);
            border-bottom-color: var(--accent-primary);
        }
    </style>
    {{-- SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

@section('content')
    <div class="flex flex-col lg:flex-row min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
        <div class="w-full lg:w-80 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col pt-8 pr-8">
            <div id="sideBarSelector">
                @include("templates.$template.blades.admin.settings.partials.sidebar")
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-8 lg:pl-16 overflow-y-auto custom-scrollbar" id="contentScrollContainer">
            <div class="max-w-4xl">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                    <div class="flex flex-col gap-2">
                        <h2 class="text-4xl font-light text-white tracking-tight leading-none">
                            {{ __('Navigation Design') }}
                        </h2>
                        <p class="text-slate-500 text-sm font-medium tracking-wide">
                            {{ __('Customize the structural layout and visibility of your platform sidebars.') }}
                        </p>
                    </div>
                    <button type="button" onclick="openCreateModal()"
                        class="px-6 py-3 bg-white/5 border border-white/10 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-white/10 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('New Menu Item') }}
                    </button>
                </div>


                {{-- Tabs --}}
                <div class="flex gap-8 border-b border-white/5 mb-8">
                    <button onclick="switchTab('admin')" id="tab-admin"
                        class="tab-btn active pb-4 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-white">
                        {{ __('Admin Sidebar') }}
                    </button>
                    <button onclick="switchTab('user')" id="tab-user"
                        class="tab-btn pb-4 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-white">
                        {{ __('User Sidebar') }}
                    </button>
                </div>

                {{-- Menu List Containers --}}
                <div id="menu-container-admin" class="menu-tab-content space-y-12 pb-24">
                    @include('templates.' . $template . '.blades.admin.settings.partials.menu_list', [
                        'menus' => $admin_menus,
                    ])
                </div>

                <div id="menu-container-user" class="menu-tab-content hidden space-y-12 pb-24">
                    @include('templates.' . $template . '.blades.admin.settings.partials.menu_list', [
                        'menus' => $user_menus,
                    ])
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div
            class="bg-[#0a0a0b] border border-white/10 rounded-3xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-8 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-xl font-medium text-white">{{ __('Create Menu Item') }}</h3>
                <button onclick="closeCreateModal()" class="text-slate-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.settings.menu.create') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label
                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Label') }}</label>
                        <input type="text" name="label" required class="flat-input text-sm" value="{{ old('label') }}"
                            placeholder="e.g. Analytics">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label
                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Type') }}</label>
                        <select name="type" id="menu_type_select" required onchange="filterParentOptions()"
                            class="flat-input text-sm bg-black appearance-none">
                            <option value="user" {{ old('type') == 'user' ? 'selected' : '' }}>{{ __('User Sidebar') }}
                            </option>
                            <option value="admin" {{ old('type') == 'admin' ? 'selected' : '' }}>
                                {{ __('Admin Sidebar') }}</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-4 p-4 bg-white/5 border border-white/5 rounded-2xl">
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="nav_mode" value="route" checked onchange="switchNavMode()"
                                class="w-4 h-4 accent-accent-primary bg-black border-white/10">
                            <span
                                class="text-xs font-bold text-slate-500 group-hover:text-white uppercase tracking-widest">{{ __('Route Name') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="nav_mode" value="url" onchange="switchNavMode()"
                                class="w-4 h-4 accent-accent-primary bg-black border-white/10">
                            <span
                                class="text-xs font-bold text-slate-500 group-hover:text-white uppercase tracking-widest">{{ __('Direct Link') }}</span>
                        </label>
                    </div>

                    <div id="route_input_container" class="flex flex-col gap-2">
                        <input type="text" name="route_name"
                            class="flat-input text-sm @error('route_name') border-accent-error @enderror"
                            value="{{ old('route_name') }}" placeholder="e.g. user.dashboard">
                        @error('route_name')
                            <span
                                class="text-[10px] text-accent-error font-bold uppercase tracking-tight">{{ $message }}</span>
                        @enderror
                        <span
                            class="text-[10px] text-slate-600 uppercase tracking-tighter">{{ __('The internal Laravel route name.') }}</span>
                    </div>

                    <div id="url_input_container" class="hidden flex flex-col gap-2">
                        <input type="text" name="url"
                            class="flat-input text-sm @error('url') border-accent-error @enderror"
                            value="{{ old('url') }}" placeholder="https://example.com/custom-page">
                        @error('url')
                            <span
                                class="text-[10px] text-accent-error font-bold uppercase tracking-tight">{{ $message }}</span>
                        @enderror
                        <span
                            class="text-[10px] text-slate-600 uppercase tracking-tighter">{{ __('An external or static relative URL.') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label
                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Sort Order') }}</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}"
                            class="flat-input text-sm">
                    </div>
                    {{-- Spacer or other field? --}}
                </div>

                <div class="flex flex-col gap-2">
                    <label
                        class="text-[10px] font-bold text-[var(--accent-primary)] uppercase tracking-widest font-bold">{{ __('Icon (SVG/HTML)') }}</label>
                    <textarea name="icon" class="flat-input text-sm h-32 resize-none" placeholder="<svg>...</svg>">{{ old('icon') }}</textarea>
                </div>

                <div class="flex flex-col gap-2">
                    <label
                        class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Parent (Optional)') }}</label>
                    <select name="parent_id" id="parent_id_select" class="flat-input text-sm bg-black appearance-none">
                        <option value="">{{ __('No Parent') }}</option>
                        @foreach ($admin_menus as $menu)
                            <option value="{{ $menu->id }}" data-type="admin"
                                {{ old('parent_id') == $menu->id ? 'selected' : '' }}>{{ __('Admin') }} >
                                {{ $menu->label }}</option>
                        @endforeach
                        @foreach ($user_menus as $menu)
                            <option value="{{ $menu->id }}" data-type="user"
                                {{ old('parent_id') == $menu->id ? 'selected' : '' }}>{{ __('User') }} >
                                {{ $menu->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex justify-end gap-4">
                    <button type="button" onclick="closeCreateModal()"
                        class="px-6 py-3 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-white transition-colors">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-accent-primary text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-accent-secondary transition-all">
                        {{ __('Create Item') }}
                    </button>
                </div>
                @if ($errors->any())
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            openCreateModal();
                            @if (old('nav_mode') == 'url')
                                $('input[name="nav_mode"][value="url"]').prop('checked', true);
                                switchNavMode();
                            @endif
                        });
                    </script>
                @endif
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(type) {
            $('.tab-btn').removeClass('active');
            $(`#tab-${type}`).addClass('active');
            $('.menu-tab-content').addClass('hidden');
            $(`#menu-container-${type}`).removeClass('hidden');
        }

        function openCreateModal() {
            $('#createModal').removeClass('hidden').addClass('flex');
            filterParentOptions();
        }

        function filterParentOptions() {
            const selectedType = $('#menu_type_select').val();
            const $parentSelect = $('#parent_id_select');

            // Show all first, then hide ones that don't match
            $parentSelect.find('option').each(function() {
                const optType = $(this).data('type');
                if (!optType || optType === selectedType) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // If current selection is hidden, reset to "No Parent"
            const currentOption = $parentSelect.find('option:selected');
            if (currentOption.is(':hidden')) {
                $parentSelect.val('');
            }
        }

        function closeCreateModal() {
            $('#createModal').addClass('hidden').removeClass('flex');
        }

        function toggleChildren(id) {
            const $container = $(`#children-${id}`);
            const $chevron = $(`#chevron-${id}`);

            if ($container.hasClass('hidden')) {
                $container.removeClass('hidden').addClass('flex');
                $chevron.addClass('-rotate-180');
                // Intialize Sortable for children if not already done
                initChildSortable(id);
            } else {
                $container.addClass('hidden').removeClass('flex');
                $chevron.removeClass('-rotate-180');
            }
        }

        // Initialize Sortable for Parents
        function initParentSortable() {
            $('.menu-list-sortable').each(function() {
                new Sortable(this, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'bg-accent-primary/5',
                    onEnd: function(evt) {
                        const items = [];
                        $(evt.to).find('> .menu-item-wrapper').each(function() {
                            items.push($(this).data('id'));
                        });
                        saveOrder(items);
                    }
                });
            });
        }

        // Initialize Sortable for Children
        function initChildSortable(parentId) {
            const container = document.getElementById(`children-${parentId}`);
            if (container && !container.classList.contains('sortable-initialized')) {
                new Sortable(container, {
                    animation: 150,
                    handle: '.drag-handle-child',
                    ghostClass: 'bg-accent-primary/5',
                    onEnd: function(evt) {
                        const items = [];
                        $(evt.to).find('> .child-item').each(function() {
                            items.push($(this).data('id'));
                        });
                        saveOrder(items);
                    }
                });
                container.classList.add('sortable-initialized');
            }
        }

        function saveOrder(items) {
            $.ajax({
                url: "{{ route('admin.settings.menu.reorder') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    items: items
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                }
            });
        }

        $(document).ready(function() {
            initParentSortable();
        });

        function switchNavMode() {
            const mode = $('input[name="nav_mode"]:checked').val();
            if (mode === 'route') {
                $('#route_input_container').removeClass('hidden');
                $('#url_input_container').addClass('hidden');
                $('input[name="url"]').val('');
            } else {
                $('#route_input_container').addClass('hidden');
                $('#url_input_container').removeClass('hidden');
                $('input[name="route_name"]').val('');
            }
        }

        function toggleMenuVisibility(id, el) {
            const isActive = el.checked ? 1 : 0;

            $.ajax({
                url: "{{ route('admin.settings.menu.update') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    menu_id: id,
                    is_active: isActive
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON.message ||
                        '{{ __('Something went wrong. Please check your inputs.') }}';
                    toastNotification(errorMessage, 'error');
                    el.checked = !el.checked;
                }
            });
        }
    </script>
@endpush
