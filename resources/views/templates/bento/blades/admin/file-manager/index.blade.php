@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8 animate__animated animate__fadeIn">
        {{-- Header & Breadcrumbs --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex flex-col gap-2" id="file-manager-breadcrumbs">
                <h1 class="text-3xl font-black text-white tracking-tight uppercase">{{ __('File Manager') }}</h1>
                <nav
                    class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide @if (!moduleEnabled('file_manager_module')) hidden @endif">
                    @foreach ($breadcrumbs as $index => $breadcrumb)
                        <div class="flex items-center gap-2 shrink-0">
                            @if ($index > 0)
                                <svg class="w-3 h-3 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7-7" />
                                </svg>
                            @endif
                            <a href="javascript:void(0)" onclick='loadFolder({{ json_encode($breadcrumb['path']) }})'
                                class="text-[10px] font-black uppercase tracking-widest transition-all {{ $loop->last ? 'text-accent-primary cursor-default' : 'text-slate-500 hover:text-white' }}">
                                {{ $breadcrumb['name'] }}
                            </a>
                        </div>
                    @endforeach
                </nav>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" @if (moduleEnabled('file_manager_module')) onclick="openCreateModal('folder')" @endif
                    class="px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all flex items-center gap-2 group @if (!moduleEnabled('file_manager_module')) opacity-40 !cursor-not-allowed @endif">
                    <svg class="w-4 h-4 text-accent-primary group-hover:scale-110 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('New Folder') }}
                </button>
                <button type="button" @if (moduleEnabled('file_manager_module')) onclick="openCreateModal('file')" @endif
                    class="px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-white transition-all flex items-center gap-2 group @if (!moduleEnabled('file_manager_module')) opacity-40 !cursor-not-allowed @endif">
                    <svg class="w-4 h-4 text-accent-primary group-hover:scale-110 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('New File') }}
                </button>
                <button type="button" @if (moduleEnabled('file_manager_module')) onclick="$('#file-upload-input').click()" @endif
                    class="px-8 py-3 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-accent-primary/20 hover:scale-[1.05] active:scale-95 transition-all flex items-center gap-2 group @if (!moduleEnabled('file_manager_module')) opacity-40 !cursor-not-allowed @endif">
                    <svg class="w-4 h-4 group-hover:-translate-y-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    {{ __('Upload') }}
                </button>
                <input type="file" id="file-upload-input" class="hidden" onchange="handleFileUpload(this)">
            </div>
        </div>

        {{-- Message  if module is disabled --}}
        @if (!moduleEnabled('file_manager_module'))
            <div
                class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px] bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                    {{ __('File manager module disabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('File manager module is disabled. Please enable the file manager module in settings to initialize.') }}
                </p>
                <a href="{{ route('admin.settings.modules.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        {{-- Message  if sandbox mode is enabled --}}
        @if (config('app.env') === 'sandbox')
            <div
                class="relative z-10 p-8 flex flex-col items-center justify-center text-center h-[300px] bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <div
                    class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-500" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-2">
                    {{ __('Sandbox mode enabled') }}</h4>
                <p class="text-[10px] text-slate-500 max-w-[200px] mb-6 font-medium leading-relaxed italic">
                    {{ __('Sandbox mode is enabled. You can view, create, edit, delete, and move files and folders.') }}
                </p>
                <a href="{{ route('admin.settings.index') }}"
                    class="px-5 py-2 rounded-xl bg-white/5 border border-white/10 text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 hover:border-white/20 transition-all active:scale-95">
                    {{ __('Settings') }}
                </a>
            </div>
        @endif

        {{-- Browser Pane --}}
        @if (moduleEnabled('file_manager_module') && config('app.env') !== 'sandbox')
            <div id="file-manager-browser"
                class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden backdrop-blur-xl">
                <input type="hidden" id="current-path-hidden" value="{{ $current_path }}">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/5">
                                <th class="px-8 py-6 w-12">
                                    <div class="flex items-center justify-center">
                                        <input type="checkbox" id="select-all"
                                            class="w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                                    </div>
                                </th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">
                                    {{ __('Name') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] hidden md:table-cell">
                                    {{ __('Size') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] hidden lg:table-cell">
                                    {{ __('Modified') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] hidden xl:table-cell">
                                    {{ __('Permissions') }}</th>
                                <th
                                    class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($items as $item)
                                <tr class="group hover:bg-white/[0.03] transition-all duration-300 file-row"
                                    data-path="{{ $item['path'] }}" data-name="{{ $item['name'] }}">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center justify-center">
                                            <input type="checkbox"
                                                class="file-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer"
                                                data-path="{{ $item['path'] }}">
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-xl {{ $item['is_dir'] ? 'bg-accent-primary/10 text-accent-primary' : 'bg-white/5 text-slate-400' }} border border-white/5 flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                                                @if ($item['is_dir'])
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                @if ($item['is_dir'])
                                                    <a href="javascript:void(0)"
                                                        onclick='loadFolder({{ json_encode($item['path']) }})'
                                                        class="text-xs font-black text-white hover:text-accent-primary transition-colors truncate">
                                                        {{ $item['name'] }}
                                                    </a>
                                                @else
                                                    <span class="text-xs font-bold text-slate-300 truncate">
                                                        {{ $item['name'] }}
                                                    </span>
                                                @endif
                                                <span
                                                    class="text-[9px] font-black text-slate-600 uppercase tracking-widest">{{ $item['extension'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 hidden md:table-cell">
                                        <span class="text-[10px] font-bold text-slate-500">{{ $item['size'] }}</span>
                                    </td>
                                    <td class="px-8 py-5 hidden lg:table-cell">
                                        <span
                                            class="text-[10px] font-bold text-slate-500">{{ $item['last_modified'] }}</span>
                                    </td>
                                    <td class="px-8 py-5 hidden xl:table-cell">
                                        <code
                                            class="text-[10px] font-black text-accent-primary bg-accent-primary/10 px-2 py-1 rounded-lg">{{ $item['permissions'] }}</code>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center justify-end gap-2 transition-opacity">
                                            @if (!$item['is_dir'])
                                                <a href="{{ route('admin.file-manager.code-editor', ['path' => $item['path']]) }}"
                                                    target="_blank"
                                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-accent-primary hover:bg-accent-primary/10 transition-all"
                                                    title="{{ __('Edit') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.file-manager.download', ['path' => $item['path']]) }}"
                                                    target="_blank"
                                                    class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-emerald-500 hover:bg-emerald-500/10 transition-all"
                                                    title="{{ __('Download') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                            @endif
                                            <button
                                                onclick='openRenameModal({{ json_encode($item['path']) }}, {{ json_encode($item['name']) }})'
                                                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-amber-500 hover:bg-amber-500/10 transition-all"
                                                title="{{ __('Rename') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <button
                                                onclick='openPermissionModal({{ json_encode($item['path']) }}, {{ json_encode($item['permissions']) }})'
                                                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-emerald-500 hover:bg-emerald-500/10 transition-all"
                                                title="{{ __('Permissions') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </button>
                                            <button onclick='deleteItem({{ json_encode($item['path']) }})'
                                                class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-500/10 transition-all"
                                                title="{{ __('Delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center gap-4 opacity-30">
                                            <svg class="w-16 h-16" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                            <span
                                                class="text-xs font-black uppercase tracking-widest">{{ __('This directory is empty') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{-- Floating Bulk Toolbar --}}
    <div id="bulk-toolbar"
        class="fixed bottom-12 left-1/2 -translate-x-1/2 bg-secondary/80 backdrop-blur-2xl border border-white/10 px-8 py-6 rounded-[2rem] shadow-2xl z-[150] hidden items-center gap-6 animate__animated animate__fadeInUp">
        <div class="flex items-center gap-3 pr-6 border-r border-white/10">
            <span
                class="w-8 h-8 rounded-full bg-accent-primary text-white text-[10px] font-black flex items-center justify-center"
                id="selected-count">0</span>
            <span class="text-[10px] font-black text-white uppercase tracking-widest">{{ __('Selected') }}</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="openZipModal()"
                class="p-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition-all"
                title="{{ __('Zip Selected') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <line x1="12" y1="10" x2="12" y2="17" stroke-width="2"
                        stroke-linecap="round" />
                    <rect x="11" y="10" width="2" height="1" fill="currentColor" stroke="none" />
                    <rect x="11" y="12" width="2" height="1" fill="currentColor" stroke="none" />
                    <rect x="11" y="14" width="2" height="1" fill="currentColor" stroke="none" />
                </svg>
            </button>
            <button onclick="openBulkMoveModal('move')"
                class="p-3 text-slate-400 hover:text-cyan-500 hover:bg-cyan-500/10 rounded-xl transition-all"
                title="{{ __('Move Selected') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </button>
            <button onclick="openBulkMoveModal('copy')"
                class="p-3 text-slate-400 hover:text-emerald-500 hover:bg-emerald-500/10 rounded-xl transition-all"
                title="{{ __('Copy Selected') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
            </button>
            <button onclick="bulkDelete()"
                class="p-3 text-slate-400 hover:text-red-500 hover:bg-red-500/10 rounded-xl transition-all"
                title="{{ __('Delete Selected') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
            <button onclick="closeBulkToolbar()" class="ml-4 p-3 text-slate-500 hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Modals --}}
    <div id="create-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-secondary p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-8" id="modal-title"></h3>
            <div class="space-y-6">
                <div class="flex flex-col gap-3">
                    <label
                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Name') }}</label>
                    <input type="text" id="new-item-name"
                        class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                </div>
                <div class="flex items-center gap-4 pt-4">
                    <button type="button" onclick="closeModal('create-modal')"
                        class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="button" onclick="confirmCreate()"
                        class="flex-[2] py-4 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all">{{ __('Proceed') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="rename-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-secondary p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-8">{{ __('Rename Item') }}</h3>
            <div class="space-y-6">
                <div class="flex flex-col gap-3">
                    <label
                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('New Name') }}</label>
                    <input type="text" id="rename-item-name"
                        class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                </div>
                <div class="flex items-center gap-4 pt-4">
                    <button type="button" onclick="closeModal('rename-modal')"
                        class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="button" onclick="confirmRename()"
                        class="flex-[2] py-4 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all">{{ __('Rename') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="permission-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-secondary p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-8">{{ __('Change Permissions') }}</h3>
            <div class="space-y-6">
                <div class="grid grid-cols-4 gap-4 bg-white/5 border border-white/10 p-6 rounded-3xl">
                    <div class="col-span-1"></div>
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        {{ __('Read') }}</div>
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        {{ __('Write') }}</div>
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        {{ __('Exec') }}</div>

                    <div class="text-[10px] font-black text-white uppercase tracking-widest flex items-center">
                        {{ __('Owner') }}</div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-owner-r"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-owner-w"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-owner-x"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>

                    <div class="text-[10px] font-black text-white uppercase tracking-widest flex items-center">
                        {{ __('Group') }}</div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-group-r"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-group-w"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-group-x"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>

                    <div class="text-[10px] font-black text-white uppercase tracking-widest flex items-center">
                        {{ __('Public') }}</div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-public-r"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-public-w"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>
                    <div class="flex justify-center"><input type="checkbox" id="perm-public-x"
                            class="perm-checkbox w-4 h-4 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary/20 transition-all cursor-pointer">
                    </div>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-4 text-center">
                    <span
                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest mr-2">{{ __('Octal Preview') }}:</span>
                    <span id="perm-octal-preview" class="text-accent-primary font-black">0755</span>
                </div>
                <div class="flex items-center gap-4 pt-4">
                    <button type="button" onclick="closeModal('permission-modal')"
                        class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="button" onclick="confirmPermissions()"
                        class="flex-[2] py-4 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all">{{ __('Update') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="zip-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-secondary p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-8">{{ __('Create Archive') }}</h3>
            <div class="space-y-6">
                <div class="flex flex-col gap-3">
                    <label
                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Archive Name') }}</label>
                    <input type="text" id="archive-name" placeholder="archive.zip"
                        class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                </div>
                <div class="flex items-center gap-4 pt-4">
                    <button type="button" onclick="closeModal('zip-modal')"
                        class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="button" onclick="confirmZip()"
                        class="flex-[2] py-4 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all">{{ __('Zip Items') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div id="bulk-move-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-secondary p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-8" id="bulk-move-title"></h3>
            <div class="space-y-6">
                <div class="flex flex-col gap-3">
                    <label
                        class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ __('Dest. Path') }}</label>
                    <input type="text" id="bulk-move-dest" placeholder="/"
                        class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                    <p class="text-[10px] text-slate-500 font-bold">
                        {{ __('Use absolute path or relative to project root.') }}</p>
                </div>
                <div class="flex items-center gap-4 pt-4">
                    <button type="button" onclick="closeModal('bulk-move-modal')"
                        class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                    <button type="button" onclick="confirmBulkMove()" id="bulk-move-btn"
                        class="flex-[2] py-4 bg-accent-primary text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all"></button>
                </div>
            </div>
        </div>
    </div>
    <div id="delete-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-secondary p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <div class="w-16 h-16 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center mb-8 mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-2 text-center">{{ __('Delete Item?') }}
            </h3>
            <p class="text-slate-500 text-sm font-bold text-center mb-8">
                {{ __('This action is irreversible. Are you sure you want to delete this item?') }}</p>
            <div class="flex items-center gap-4">
                <button type="button" onclick="closeModal('delete-modal')"
                    class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                <button type="button" onclick="confirmDelete()"
                    class="flex-[2] py-4 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all shadow-lg shadow-red-500/20">{{ __('Delete Permanently') }}</button>
            </div>
        </div>
    </div>
    <div id="bulk-delete-modal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[200] hidden items-center justify-center p-6">
        <div
            class="bg-secondary p-10 rounded-[2.5rem] border border-white/10 shadow-2xl w-full max-w-md animate__animated animate__zoomIn">
            <div class="w-16 h-16 rounded-2xl bg-red-500/10 text-red-500 flex items-center justify-center mb-8 mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-2 text-center">
                {{ __('Delete Multiple Items?') }}</h3>
            <p class="text-slate-500 text-sm font-bold text-center mb-8">
                {{ __('Are you sure you want to delete ') }}<span id="bulk-delete-count"
                    class="text-white"></span>{{ __(' items? This action is irreversible.') }}
            </p>
            <div class="flex items-center gap-4">
                <button type="button" onclick="closeModal('bulk-delete-modal')"
                    class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-white transition-all">{{ __('Cancel') }}</button>
                <button type="button" onclick="confirmBulkDelete()"
                    class="flex-[2] py-4 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-[1.05] active:scale-95 transition-all shadow-lg shadow-red-500/20">{{ __('Delete Permanently') }}</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentTargetPath = @json($current_path);
        let itemToRename = null;
        let itemToDelete = null;
        let createType = 'folder';

        function loadFolder(path) {
            toastNotification("{{ __('Loading...') }}", 'info');
            $.get("{{ route('admin.file-manager.index') }}", {
                path: path
            }, function(response) {
                const $html = $('<div>').append($.parseHTML(response));

                $('#file-manager-breadcrumbs').html($html.find('#file-manager-breadcrumbs').html());
                $('#file-manager-browser').html($html.find('#file-manager-browser').html());

                currentTargetPath = $('#current-path-hidden').val();

                // Update URL
                const url = new URL(window.location);
                url.searchParams.set('path', path);
                window.history.pushState({
                    path: path
                }, '', url);

                closeBulkToolbar();
            }).fail(function() {
                toastNotification("{{ __('Failed to load directory.') }}", 'error');
            });
        }

        window.onpopstate = function(event) {
            const path = new URLSearchParams(window.location.search).get('path') || @json($base_path);
            $.get("{{ route('admin.file-manager.index') }}", {
                path: path
            }, function(response) {
                const $html = $('<div>').append($.parseHTML(response));
                $('#file-manager-breadcrumbs').html($html.find('#file-manager-breadcrumbs').html());
                $('#file-manager-browser').html($html.find('#file-manager-browser').html());
                currentTargetPath = $('#current-path-hidden').val();
                closeBulkToolbar();
            });
        };

        function openCreateModal(type) {
            createType = type;
            $('#modal-title').text(type === 'folder' ? "{{ __('Create New Folder') }}" : "{{ __('Create New File') }}");
            $('#new-item-name').val('').focus();
            $('#create-modal').removeClass('hidden').addClass('flex');
        }

        function openRenameModal(path, name) {
            itemToRename = path;
            $('#rename-item-name').val(name).focus();
            $('#rename-modal').removeClass('hidden').addClass('flex');
        }

        function closeModal(id) {
            $(`#${id}`).addClass('hidden').removeClass('flex');
        }

        function confirmCreate() {
            const name = $('#new-item-name').val();
            if (!name) return toastNotification("{{ __('Please enter a name.') }}", 'warning');

            $.ajax({
                url: "{{ route('admin.file-manager.create') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    path: currentTargetPath,
                    name: name,
                    type: createType
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('create-modal');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Failed to create item.') }}",
                        'error');
                }
            });
        }

        function confirmRename() {
            const newName = $('#rename-item-name').val();
            if (!newName) return toastNotification("{{ __('Please enter a new name.') }}", 'warning');

            $.ajax({
                url: "{{ route('admin.file-manager.rename') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    old_path: itemToRename,
                    new_name: newName
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('rename-modal');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Failed to rename item.') }}",
                        'error');
                }
            });
        }

        function deleteItem(path) {
            itemToDelete = path;
            $('#delete-modal').removeClass('hidden').addClass('flex');
        }

        function confirmDelete() {
            if (!itemToDelete) return;

            $.ajax({
                url: "{{ route('admin.file-manager.delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    path: itemToDelete
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('delete-modal');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Failed to delete item.') }}",
                        'error');
                }
            });
        }

        function handleFileUpload(input) {
            if (!input.files || !input.files[0]) return;

            const formData = new FormData();
            formData.append('file', input.files[0]);
            formData.append('path', currentTargetPath);
            formData.append('_token', "{{ csrf_token() }}");

            toastNotification("{{ __('Uploading...') }}", 'info');

            $.ajax({
                url: "{{ route('admin.file-manager.upload') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastNotification(response.message, 'success');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Upload failed.') }}", 'error');
                }
            });
        }

        // Drag & Drop Simulation/Support
        $(document)[0].addEventListener('dragover', (e) => e.preventDefault());
        $(document)[0].addEventListener('drop', (e) => {
            e.preventDefault();
            if (e.dataTransfer.files.length > 0) {
                const input = document.getElementById('file-upload-input');
                input.files = e.dataTransfer.files;
                handleFileUpload(input);
            }
        });

        // Handle Escape key for modals
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal('create-modal');
                closeModal('rename-modal');
                closeModal('permission-modal');
                closeModal('zip-modal');
                closeModal('bulk-move-modal');
                closeModal('delete-modal');
                closeModal('bulk-delete-modal');
            }
        });

        function openPermissionModal(path, currentPerms) {
            itemToRename = path;
            const perms = String(currentPerms).padStart(4, '0');
            const owner = parseInt(perms[1]);
            const group = parseInt(perms[2]);
            const public = parseInt(perms[3]);

            $('#perm-owner-r').prop('checked', owner & 4);
            $('#perm-owner-w').prop('checked', owner & 2);
            $('#perm-owner-x').prop('checked', owner & 1);

            $('#perm-group-r').prop('checked', group & 4);
            $('#perm-group-w').prop('checked', group & 2);
            $('#perm-group-x').prop('checked', group & 1);

            $('#perm-public-r').prop('checked', public & 4);
            $('#perm-public-w').prop('checked', public & 2);
            $('#perm-public-x').prop('checked', public & 1);

            updateOctalPreview();
            $('#permission-modal').removeClass('hidden').addClass('flex');
        }

        function updateOctalPreview() {
            let owner = 0;
            if ($('#perm-owner-r').is(':checked')) owner += 4;
            if ($('#perm-owner-w').is(':checked')) owner += 2;
            if ($('#perm-owner-x').is(':checked')) owner += 1;

            let group = 0;
            if ($('#perm-group-r').is(':checked')) group += 4;
            if ($('#perm-group-w').is(':checked')) group += 2;
            if ($('#perm-group-x').is(':checked')) group += 1;

            let public = 0;
            if ($('#perm-public-r').is(':checked')) public += 4;
            if ($('#perm-public-w').is(':checked')) public += 2;
            if ($('#perm-public-x').is(':checked')) public += 1;

            const octal = `0${owner}${group}${public}`;
            $('#perm-octal-preview').text(octal);
            return octal;
        }

        $(document).on('change', '.perm-checkbox', updateOctalPreview);

        function confirmPermissions() {
            const perms = updateOctalPreview();

            $.ajax({
                url: "{{ route('admin.file-manager.permission') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    path: itemToRename,
                    permissions: perms
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('permission-modal');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message ||
                        "{{ __('Failed to update permissions.') }}",
                        'error');
                }
            });
        }

        // Multi-select & Bulk Logic
        let lastCheckedIdx = null;

        function handleItemSelection(index, checked, shiftKey, ctrlKey) {
            const $checkboxes = $('.file-checkbox');

            if (shiftKey && lastCheckedIdx !== null) {
                const start = Math.min(index, lastCheckedIdx);
                const end = Math.max(index, lastCheckedIdx);
                $checkboxes.slice(start, end + 1).prop('checked', true);
            } else if (ctrlKey) {
                $checkboxes.eq(index).prop('checked', !checked);
            } else {
                // For web-manager, we'll keep it as a toggle if no modifiers are held, 
                // OR we can make it behave exactly like desktop. 
                // Let's stick to "click to toggle" but support Shift for range.
                $checkboxes.eq(index).prop('checked', !checked);
            }

            lastCheckedIdx = index;
            updateBulkToolbar();
        }

        $(document).on('click', '.file-row', function(e) {
            if ($(e.target).closest('a, button').length) return;

            const $cb = $(this).find('.file-checkbox');
            const index = $('.file-checkbox').index($cb);
            const isSelectableClick = $(e.target).closest('input[type="checkbox"]').length;

            if (isSelectableClick) {
                // Let the checkbox default click happen but update logic
                lastCheckedIdx = index;
                updateBulkToolbar();
                return;
            }

            e.preventDefault();
            handleItemSelection(index, $cb.prop('checked'), e.shiftKey, e.ctrlKey || e.metaKey);
        });

        // Specific fix for checkbox-only clicks to support Shift
        $(document).on('click', '.file-checkbox', function(e) {
            if (e.shiftKey && lastCheckedIdx !== null) {
                e.preventDefault();
                const index = $('.file-checkbox').index(this);
                handleItemSelection(index, false, true, false);
            }
        });

        $('#select-all').on('change', function() {
            $('.file-checkbox').prop('checked', this.checked);
            updateBulkToolbar();
        });

        function updateBulkToolbar() {
            const count = $('.file-checkbox:checked').length;
            $('#selected-count').text(count);
            if (count > 0) {
                $('#bulk-toolbar').removeClass('hidden').addClass('flex');
            } else {
                $('#bulk-toolbar').addClass('hidden').removeClass('flex');
            }
        }

        function closeBulkToolbar() {
            $('.file-checkbox, #select-all').prop('checked', false);
            updateBulkToolbar();
        }

        function getSelectedPaths() {
            return $('.file-checkbox:checked').map(function() {
                return $(this).data('path');
            }).get();
        }

        function bulkDelete() {
            const paths = getSelectedPaths();
            if (!paths.length) return;
            $('#bulk-delete-count').text(paths.length);
            $('#bulk-delete-modal').removeClass('hidden').addClass('flex');
        }

        function confirmBulkDelete() {
            const paths = getSelectedPaths();
            if (!paths.length) return;

            toastNotification("{{ __('Deleting...') }}", 'info');

            $.ajax({
                url: "{{ route('admin.file-manager.bulk-delete') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    paths: paths
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('bulk-delete-modal');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Bulk delete failed.') }}",
                        'error');
                }
            });
        }

        function openZipModal() {
            $('#archive-name').val('archive.zip').focus();
            $('#zip-modal').removeClass('hidden').addClass('flex');
        }

        function confirmZip() {
            const name = $('#archive-name').val();
            const paths = getSelectedPaths();
            if (!name || !paths.length) return;

            toastNotification("{{ __('Creating archive...') }}", 'info');

            $.ajax({
                url: "{{ route('admin.file-manager.bulk-zip') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    paths: paths,
                    name: name,
                    dest_dir: currentTargetPath
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('zip-modal');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Zip failed.') }}", 'error');
                }
            });
        }

        let bulkOperationMode = 'move';

        function openBulkMoveModal(mode) {
            bulkOperationMode = mode;
            $('#bulk-move-title').text(mode === 'move' ? "{{ __('Move Selected') }}" : "{{ __('Copy Selected') }}");
            $('#bulk-move-btn').text(mode === 'move' ? "{{ __('Move Items') }}" : "{{ __('Copy Items') }}");
            $('#bulk-move-dest').val(currentTargetPath).focus();
            $('#bulk-move-modal').removeClass('hidden').addClass('flex');
        }

        function confirmBulkMove() {
            const dest = $('#bulk-move-dest').val();
            const paths = getSelectedPaths();
            if (!dest || !paths.length) return;

            toastNotification(bulkOperationMode === 'move' ? "{{ __('Moving...') }}" : "{{ __('Copying...') }}",
                'info');

            const url = bulkOperationMode === 'move' ?
                "{{ route('admin.file-manager.bulk-move') }}" :
                "{{ route('admin.file-manager.bulk-copy') }}";

            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    paths: paths,
                    new_dir: dest
                },
                success: function(response) {
                    toastNotification(response.message, 'success');
                    closeModal('bulk-move-modal');
                    loadFolder(currentTargetPath);
                },
                error: function(xhr) {
                    toastNotification(xhr.responseJSON?.message || "{{ __('Bulk operation failed.') }}",
                        'error');
                }
            });
        }
    </script>
@endpush
