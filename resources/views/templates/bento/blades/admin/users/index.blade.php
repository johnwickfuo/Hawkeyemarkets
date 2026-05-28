@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div class="space-y-8">
        {{-- User Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-accent-primary/20 rounded-lg text-accent-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Total Users') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['total']) }}</h4>
                    </div>
                </div>
            </div>

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
                            {{ __('Active') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['active']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-red-500/20 rounded-lg text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Banned') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['banned']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-yellow-500/20 rounded-lg text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('Email Unverified') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['email_unverified']) }}</h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-secondary border border-white/5 rounded-xl p-5 relative overflow-hidden group hover:border-white/10 transition-colors">
                <div class="relative z-10 flex items-center gap-4">
                    <div class="p-3 bg-purple-500/20 rounded-lg text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-text-secondary text-[10px] uppercase font-bold tracking-wider mb-0.5">
                            {{ __('KYC Pending') }}</p>
                        <h4 class="text-xl font-bold text-white">{{ number_format($stats['kyc_pending']) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users Table Section --}}
        <div class="bg-secondary border border-white/5 rounded-2xl overflow-hidden relative" id="users-wrapper">
            {{-- Loading Spinner Overlay --}}
            <div id="loading-spinner"
                class="hidden absolute inset-0 bg-secondary/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center transition-opacity duration-300">
                <div class="w-10 h-10 border-4 border-accent-primary border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-3 text-text-secondary font-medium">{{ __('Loading users...') }}</p>
            </div>

            {{-- Table Filter Header --}}
            <div class="p-6 border-b border-white/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    {{ __('Users List') }}
                </h3>

                {{-- Filters --}}
                <form id="filter-form" action="{{ route('admin.users.index') }}" method="GET"
                    class="flex flex-wrap gap-2 items-center">
                    <div class="relative">
                        <input type="text" name="search" placeholder="{{ __('Name, username or email...') }}"
                            value="{{ request('search') }}"
                            class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-white text-base placeholder-text-secondary focus:ring-1 focus:ring-accent-primary focus:border-accent-primary outline-none w-full lg:w-64">
                    </div>

                    {{-- Custom Status Filter --}}
                    <div class="relative custom-dropdown" id="status-filter-dropdown">
                        <input type="hidden" name="status" id="status-input" value="{{ request('status', 'all') }}">
                        <button type="button"
                            class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[120px] dropdown-btn cursor-pointer">
                            <span class="selected-label">
                                @if (request('status') == 'active')
                                    {{ __('Active') }}
                                @elseif(request('status') == 'banned')
                                    {{ __('Banned') }}
                                @else
                                    {{ __('All Status') }}
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div
                            class="absolute top-full left-0 mt-2 w-full bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('status', 'all') == 'all' ? 'text-white bg-white/5' : '' }}"
                                data-value="all">{{ __('All Status') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('status') == 'active' ? 'text-white bg-white/5' : '' }}"
                                data-value="active">{{ __('Active') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('status') == 'banned' ? 'text-white bg-white/5' : '' }}"
                                data-value="banned">{{ __('Banned') }}</div>
                        </div>
                    </div>

                    {{-- Custom KYC Filter --}}
                    <div class="relative custom-dropdown" id="kyc-filter-dropdown">
                        <input type="hidden" name="kyc_status" id="kyc-input"
                            value="{{ request('kyc_status', 'all') }}">
                        <button type="button"
                            class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[140px] dropdown-btn cursor-pointer">
                            <span class="selected-label">
                                @if (request('kyc_status') == 'pending')
                                    {{ __('Pending KYC') }}
                                @elseif(request('kyc_status') == 'approved')
                                    {{ __('Verified KYC') }}
                                @elseif(request('kyc_status') == 'rejected')
                                    {{ __('Rejected KYC') }}
                                @else
                                    {{ __('All KYC') }}
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div
                            class="absolute top-full left-0 mt-2 w-full bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('kyc_status', 'all') == 'all' ? 'text-white bg-white/5' : '' }}"
                                data-value="all">{{ __('All KYC Status') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('kyc_status') == 'pending' ? 'text-white bg-white/5' : '' }}"
                                data-value="pending">{{ __('Pending') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('kyc_status') == 'approved' ? 'text-white bg-white/5' : '' }}"
                                data-value="approved">{{ __('Verified') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('kyc_status') == 'rejected' ? 'text-white bg-white/5' : '' }}"
                                data-value="rejected">{{ __('Rejected') }}</div>
                        </div>
                    </div>


                    {{-- Custom Email Verified Filter --}}
                    <div class="relative custom-dropdown" id="email-verified-filter-dropdown">
                        <input type="hidden" name="email_verified" id="email-verified-input"
                            value="{{ request('email_verified', 'all') }}">
                        <button type="button"
                            class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/5 transition-all flex items-center justify-between gap-3 min-w-[160px] dropdown-btn cursor-pointer">
                            <span class="selected-label">
                                @if (request('email_verified') == '1')
                                    {{ __('Email Verified') }}
                                @elseif(request('email_verified') == '0')
                                    {{ __('Email Unverified') }}
                                @else
                                    {{ __('All Emails') }}
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-text-secondary transition-transform dropdown-icon" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div
                            class="absolute top-full left-0 mt-2 w-full bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-[60] py-1 hidden dropdown-menu animate-in fade-in slide-in-from-top-2 duration-200">
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('email_verified', 'all') == 'all' ? 'text-white bg-white/5' : '' }}"
                                data-value="all">{{ __('All Emails') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('email_verified') == '1' ? 'text-white bg-white/5' : '' }}"
                                data-value="1">{{ __('Verified') }}</div>
                            <div class="dropdown-option px-3 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 cursor-pointer transition-colors {{ request('email_verified') == '0' ? 'text-white bg-white/5' : '' }}"
                                data-value="0">{{ __('Unverified') }}</div>
                        </div>
                    </div>

                    <button type="submit"
                        class="bg-accent-primary hover:bg-accent-primary/90 text-white px-4 py-1.5 rounded-lg text-sm font-bold shadow-lg shadow-accent-primary/20 transition-all cursor-pointer whitespace-nowrap">
                        {{ __('Apply Filters') }}
                    </button>

                    {{-- Export Dropdown --}}
                    <div class="relative group ml-2">
                        <button type="button"
                            class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white hover:bg-white/10 transition-colors flex items-center gap-2 cursor-pointer export-trigger">
                            <svg class="w-4 h-4 text-text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            {{ __('Export') }}
                        </button>
                        <div
                            class="absolute right-0 top-full mt-2 w-32 bg-[#0F172A] border border-white/10 rounded-lg shadow-xl z-50 flex flex-col hidden export-dropdown-menu">
                            <a href="#" data-export="csv"
                                class="block px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 first:rounded-t-lg text-left cursor-pointer">{{ __('CSV') }}</a>
                            <a href="#" data-export="pdf"
                                class="block px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 text-left cursor-pointer">{{ __('PDF') }}</a>
                            <a href="#" data-export="sql"
                                class="block px-4 py-2 text-sm text-text-secondary hover:text-white hover:bg-white/5 last:rounded-b-lg text-left cursor-pointer">{{ __('SQL') }}</a>
                        </div>
                    </div>
                </form>
            </div>

            <div id="table-content">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-white/5 border-b border-white/5">
                                <th class="px-6 py-4 text-left w-10">
                                    <input type="checkbox" id="select-all-users"
                                        class="w-5 h-5 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group/sort"
                                    data-sort="user">
                                    <div class="flex items-center gap-2">
                                        {{ __('User') }}
                                        <svg class="w-3 h-3 transition-colors {{ request('sort') == 'user' ? 'text-accent-primary' : 'text-text-secondary opacity-0 group-hover/sort:opacity-100' }} {{ request('direction') == 'asc' ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group/sort"
                                    data-sort="balance">
                                    <div class="flex items-center gap-2">
                                        {{ __('Balance') }}
                                        <svg class="w-3 h-3 transition-colors {{ request('sort') == 'balance' ? 'text-accent-primary' : 'text-text-secondary opacity-0 group-hover/sort:opacity-100' }} {{ request('direction') == 'asc' ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group/sort"
                                    data-sort="kyc">
                                    <div class="flex items-center gap-2">
                                        {{ __('KYC') }}
                                        <svg class="w-3 h-3 transition-colors {{ request('sort') == 'kyc' ? 'text-accent-primary' : 'text-text-secondary opacity-0 group-hover/sort:opacity-100' }} {{ request('direction') == 'asc' ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group/sort"
                                    data-sort="created_at">
                                    <div class="flex items-center gap-2">
                                        {{ __('Joined') }}
                                        <svg class="w-3 h-3 transition-colors {{ request('sort', 'created_at') == 'created_at' ? 'text-accent-primary' : 'text-text-secondary opacity-0 group-hover/sort:opacity-100' }} {{ request('direction', 'desc') == 'asc' ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-text-secondary uppercase tracking-wider cursor-pointer group/sort"
                                    data-sort="status">
                                    <div class="flex items-center gap-2">
                                        {{ __('Status') }}
                                        <svg class="w-3 h-3 transition-colors {{ request('sort') == 'status' ? 'text-accent-primary' : 'text-text-secondary opacity-0 group-hover/sort:opacity-100' }} {{ request('direction') == 'asc' ? 'rotate-180' : '' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold text-text-secondary uppercase tracking-wider">
                                    {{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($users as $user)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                            class="user-checkbox w-5 h-5 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-accent-primary/20 flex items-center justify-center text-accent-primary font-bold text-xs">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <span
                                                    class="text-sm font-bold text-white block">{{ $user->fullname }}</span>
                                                <span class="text-[10px] text-text-secondary">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-white">{{ showAmount($user->balance) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $kyc = $user->kyc->first(); @endphp
                                        @if ($kyc)
                                            @if ($kyc->status == 'pending')
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-yellow-500/10 text-yellow-500 text-[10px] font-bold uppercase">{{ __('Pending') }}</span>
                                            @elseif($kyc->status == 'approved')
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-bold uppercase">{{ __('Verified') }}</span>
                                            @else
                                                <span
                                                    class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-500 text-[10px] font-bold uppercase">{{ __('Rejected') }}</span>
                                            @endif
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-white/5 text-text-secondary text-[10px] font-bold uppercase">{{ __('Not Submitted') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="text-sm text-text-secondary">{{ $user->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($user->status == 'active')
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-bold uppercase">{{ __('Active') }}</span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 text-[10px] font-bold uppercase">{{ $user->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.detail', $user->id) }}"
                                                class="p-1.5 rounded-lg bg-white/5 text-white hover:bg-accent-primary hover:text-white transition-all group cursor-pointer"
                                                title="{{ __('View Profile') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            @if ($user->status == 'active')
                                                <button
                                                    class="p-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all cursor-pointer open-confirm-modal"
                                                    data-action="ban" data-id="{{ $user->id }}"
                                                    data-name="{{ $user->fullname }}" title="{{ __('Ban User') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                        </path>
                                                    </svg>
                                                </button>
                                            @else
                                                <button
                                                    class="p-1.5 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white transition-all cursor-pointer open-confirm-modal"
                                                    data-action="unban" data-id="{{ $user->id }}"
                                                    data-name="{{ $user->fullname }}" title="{{ __('Unban User') }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            <button
                                                class="p-1.5 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white transition-all cursor-pointer open-confirm-modal"
                                                data-action="delete" data-id="{{ $user->id }}"
                                                data-name="{{ $user->fullname }}" title="{{ __('Delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
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
                                    <td colspan="7" class="px-6 py-12 text-center text-text-secondary">
                                        {{ __('No users found matching your criteria.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages() || $users->count() > 0)
                    <div class="p-6 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
                        {{-- Bulk Action Panel --}}
                        <div class="flex items-center gap-2">
                            <div class="relative custom-dropdown" id="bulk-action-dropdown">
                                <select id="bulk-action-select"
                                    class="bg-[#1E293B] border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:ring-1 focus:ring-accent-primary outline-none cursor-pointer">
                                    <option value="">{{ __('Bulk Actions') }}</option>
                                    <option value="delete">{{ __('Delete Selected') }}</option>
                                    <option value="ban">{{ __('Ban Users') }}</option>
                                    <option value="unban">{{ __('Unban Users') }}</option>
                                    <option value="email">{{ __('Bulk Email') }}</option>
                                    <option value="verify_email">{{ __('Verify Emails') }}</option>
                                </select>
                            </div>
                            <button type="button" id="apply-bulk-action"
                                class="bg-accent-primary hover:bg-accent-primary/90 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">
                                {{ __('Go') }}
                            </button>
                        </div>

                        {{-- Pagination --}}
                        @if ($users->hasPages())
                            <div class="ajax-pagination">
                                {{ $users->links('templates.bento.blades.partials.pagination') }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- Export Modal --}}
    <div id="export-modal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
            <div
                class="bg-secondary border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-6 border-b border-white/5 flex items-center justify-between bg-white/5">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-accent-primary" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        {{ __('Export Settings') }}
                    </h3>
                    <button class="text-text-secondary hover:text-white transition-colors modal-close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent-primary"></span>
                            {{ __('Select Columns to Export') }}
                        </p>
                        <div class="grid grid-cols-2 gap-3" id="export-columns-container">
                            @php
                                $columns = [
                                    'id' => ['label' => 'ID', 'default' => true],
                                    'username' => ['label' => 'Username', 'default' => true],
                                    'fullname' => ['label' => 'Full Name', 'default' => true],
                                    'email' => ['label' => 'Email', 'default' => true],
                                    'balance' => ['label' => 'Balance', 'default' => true],
                                    'status' => ['label' => 'Status', 'default' => true],
                                    'kyc_status' => ['label' => 'KYC Status', 'default' => false],
                                    'first_name' => ['label' => 'First Name', 'default' => false],
                                    'last_name' => ['label' => 'Last Name', 'default' => false],
                                    'referral_code' => ['label' => 'Referral Code', 'default' => false],
                                    'referred_by' => ['label' => 'Referred By', 'default' => false],
                                    'created_at' => ['label' => 'Joined At', 'default' => true],
                                ];
                            @endphp
                            @foreach ($columns as $key => $col)
                                <label
                                    class="flex items-center gap-3 p-3 rounded-xl border border-white/5 bg-white/5 hover:border-accent-primary/50 transition-all cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="export_cols[]" value="{{ $key }}"
                                            {{ $col['default'] ? 'checked' : '' }}
                                            class="w-5 h-5 rounded border-white/10 bg-white/5 text-accent-primary focus:ring-accent-primary focus:ring-offset-0 cursor-pointer">
                                    </div>
                                    <span
                                        class="text-sm text-text-secondary group-hover:text-white transition-colors">{{ __($col['label']) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/5 flex gap-3">
                        <input type="hidden" id="pending-export-type" value="">
                        <button type="button"
                            class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" id="confirm-export"
                            class="flex-1 px-4 py-3 rounded-xl bg-accent-primary text-white font-bold hover:bg-accent-primary/90 shadow-lg shadow-accent-primary/20 transition-all cursor-pointer">
                            {{ __('Generate Export') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirm-modal" class="fixed inset-0 z-[110] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm modal-close"></div>
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm p-6">
            <div
                class="bg-secondary border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-8 text-center">
                    <div id="confirm-icon-container"
                        class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center">
                        {{-- Icon injected via JS --}}
                    </div>
                    <h3 id="confirm-title" class="text-xl font-bold text-white mb-2"></h3>
                    <p id="confirm-message" class="text-text-secondary text-sm mb-8"></p>

                    <div class="flex gap-3">
                        <button type="button"
                            class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-white font-medium hover:bg-white/5 transition-all modal-close cursor-pointer">
                            {{ __('Cancel') }}
                        </button>
                        <button type="button" id="confirm-action-btn"
                            class="flex-1 px-4 py-3 rounded-xl text-white font-bold shadow-lg transition-all cursor-pointer">
                            {{ __('Confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // AJAX Pagination
            $(document).on('click', '.ajax-pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                loadTable(url);
            });

            // AJAX Filtering
            $('#filter-form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action') + '?' + $(this).serialize();
                loadTable(url);
            });

            function loadTable(url) {
                $('#loading-spinner').removeClass('hidden').addClass('flex');
                $('#table-content').css('opacity', '0.5');

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#users-wrapper').html($(data).find('#users-wrapper').html());
                        window.history.pushState({}, '', url);
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            "{{ __('Failed to load content.') }}";
                        toastNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                        $('#table-content').css('opacity', '1');
                    }
                });
            }

            // Custom Dropdown Generic Logic
            $(document).on('click', '.custom-dropdown .dropdown-btn', function(e) {
                e.stopPropagation();
                const currentDropdown = $(this).closest('.custom-dropdown');
                const menu = currentDropdown.find('.dropdown-menu');
                const icon = currentDropdown.find('.dropdown-icon');

                // Close other dropdowns
                $('.custom-dropdown .dropdown-menu').not(menu).addClass('hidden');
                $('.custom-dropdown .dropdown-icon').not(icon).removeClass('rotate-180');
                $('.export-dropdown-menu').addClass('hidden');

                menu.toggleClass('hidden');
                icon.toggleClass('rotate-180');
            });

            $(document).on('click', function() {
                $('.custom-dropdown .dropdown-menu').addClass('hidden');
                $('.custom-dropdown .dropdown-icon').removeClass('rotate-180');
                $('.export-dropdown-menu').addClass('hidden');
            });

            $(document).on('click', '.custom-dropdown .dropdown-option', function() {
                const dropdown = $(this).closest('.custom-dropdown');
                const val = $(this).data('value');
                const text = $(this).text();
                const input = dropdown.find('input[type="hidden"]');
                const label = dropdown.find('.selected-label');

                input.val(val);
                label.text(text);

                dropdown.find('.dropdown-option').removeClass('text-white bg-white/5');
                $(this).addClass('text-white bg-white/5');

                dropdown.find('.dropdown-menu').addClass('hidden');
                dropdown.find('.dropdown-icon').removeClass('rotate-180');
            });

            // Table Sorting Logic
            $(document).on('click', 'th[data-sort]', function() {
                const sort = $(this).data('sort');
                let direction = 'asc';

                // If already sorting by this column, toggle direction
                const currentSort = new URLSearchParams(window.location.search).get('sort');
                const currentDir = new URLSearchParams(window.location.search).get('direction');

                if (currentSort === sort) {
                    direction = currentDir === 'asc' ? 'desc' : 'asc';
                }

                const url = new URL(window.location.href);
                url.searchParams.set('sort', sort);
                url.searchParams.set('direction', direction);

                loadTable(url.toString());
            });

            // Export Dropdown
            $(document).on('click', '.export-trigger', function(e) {
                e.stopPropagation();
                // Close other custom dropdowns
                $('.custom-dropdown .dropdown-menu').addClass('hidden');
                $('.custom-dropdown .dropdown-icon').removeClass('rotate-180');

                $('.export-dropdown-menu').toggleClass('hidden');
            });

            // Handle Export Menu Clicks
            $(document).on('click', '.export-dropdown-menu a', function(e) {
                e.preventDefault();
                var exportType = $(this).data('export');

                if (exportType === 'sql') {
                    var baseUrl = $('#filter-form').attr('action');
                    var formData = $('#filter-form').serialize();
                    var finalUrl = baseUrl + '?' + formData + '&export=sql';
                    window.open(finalUrl, '_blank');
                    $('.export-dropdown-menu').addClass('hidden');
                    return;
                }

                $('#pending-export-type').val(exportType);
                $('#export-modal').removeClass('hidden').addClass('flex');
                $('.export-dropdown-menu').addClass('hidden');
            });

            // Close Modal
            $(document).on('click', '.modal-close', function() {
                $('#export-modal').addClass('hidden').removeClass('flex');
                $('#confirm-modal').addClass('hidden').removeClass('flex');
            });

            // Confirm Export
            $('#confirm-export').on('click', function() {
                var exportType = $('#pending-export-type').val();
                var selectedCols = [];
                $('input[name="export_cols[]"]:checked').each(function() {
                    selectedCols.push($(this).val());
                });

                if (selectedCols.length === 0) {
                    toastNotification('Please select at least one column.', 'warning');
                    return;
                }

                var baseUrl = $('#filter-form').attr('action');
                var formData = $('#filter-form').serialize();
                var finalUrl = baseUrl + '?' + formData + '&export=' + exportType + '&columns=' +
                    selectedCols.join(',');

                window.open(finalUrl, '_blank');
                $('#export-modal').addClass('hidden').removeClass('flex');
            });
            // Select All Functionality
            $(document).on('change', '#select-all-users', function() {
                $('.user-checkbox').prop('checked', $(this).prop('checked'));
            });

            $(document).on('change', '.user-checkbox', function() {
                if ($('.user-checkbox:checked').length == $('.user-checkbox').length) {
                    $('#select-all-users').prop('checked', true);
                } else {
                    $('#select-all-users').prop('checked', false);
                }
            });

            // Handle Bulk Actions
            $('#apply-bulk-action').on('click', function() {
                const action = $('#bulk-action-select').val();
                const selectedIds = $('.user-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    toastNotification('Please select at least one user.', 'warning');
                    return;
                }

                if (!action) {
                    toastNotification('{{ __('Please select an action.') }}', 'warning');
                    return;
                }

                if (action === 'email') {
                    window.location.href = "{{ route('admin.users.bulk-email') }}?ids=" + selectedIds
                        .join(',');
                    return;
                }

                if (!confirm(`Are you sure you want to ${action.replace('_', ' ')} the selected users?`)) {
                    return;
                }

                $('#loading-spinner').removeClass('hidden').addClass('flex');

                $.ajax({
                    url: "{{ route('admin.users.bulk-action') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds,
                        action: action
                    },
                    success: function(response) {
                        if (response.success || response.status === 'success') {
                            loadTable(window.location.href);
                            toastNotification(response.message, 'success');
                        } else {
                            toastNotification(response.message || 'An error occurred.',
                                'error');
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON ? xhr.responseJSON.message :
                            'An error occurred while processing bulk action.';
                        toastNotification(error, 'error');
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                    }
                });
            });

            // Individual Action Confirmation Modal
            let currentUserId = null;
            let currentAction = null;

            $(document).on('click', '.open-confirm-modal', function() {
                currentUserId = $(this).data('id');
                currentAction = $(this).data('action');
                const userName = $(this).data('name');

                let title, message, icon, iconBg, btnClass;

                if (currentAction === 'ban') {
                    title = "{{ __('Ban User') }}";
                    message =
                        `{{ __('Are you sure you want to ban') }} <strong>${userName}</strong>? {{ __('They will no longer be able to access their account.') }}`;
                    icon =
                        '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>';
                    iconBg = 'bg-red-500/20 text-red-500';
                    btnClass = 'bg-red-500 hover:bg-red-600 shadow-red-500/20';
                } else if (currentAction === 'unban') {
                    title = "{{ __('Unban User') }}";
                    message = `{{ __('Are you sure you want to unban') }} <strong>${userName}</strong>?`;
                    icon =
                        '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                    iconBg = 'bg-emerald-500/20 text-emerald-500';
                    btnClass = 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20';
                } else if (currentAction === 'delete') {
                    title = "{{ __('Delete User') }}";
                    message =
                        `{{ __('Are you sure you want to delete') }} <strong>${userName}</strong>? {{ __('This action is permanent and cannot be undone.') }}`;
                    icon =
                        '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>';
                    iconBg = 'bg-red-500/20 text-red-500';
                    btnClass = 'bg-red-500 hover:bg-red-600 shadow-red-500/20';
                }

                $('#confirm-icon-container').html(icon).removeClass().addClass(
                    `w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center ${iconBg}`);
                $('#confirm-title').text(title);
                $('#confirm-message').html(message);
                $('#confirm-action-btn').removeClass().addClass(
                    `flex-1 px-4 py-3 rounded-xl text-white font-bold shadow-lg transition-all cursor-pointer ${btnClass}`
                );

                $('#confirm-modal').removeClass('hidden').addClass('flex');
            });

            $('#confirm-action-btn').on('click', function() {
                if (!currentUserId || !currentAction) return;

                $('#loading-spinner').removeClass('hidden').addClass('flex');
                $('#confirm-modal').addClass('hidden').removeClass('flex');

                $.ajax({
                    url: "{{ route('admin.users.bulk-action') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: [currentUserId],
                        action: currentAction
                    },
                    success: function(response) {
                        if (response.success || response.status === 'success') {
                            loadTable(window.location.href);
                            toastNotification(response.message, 'success');
                        } else {
                            toastNotification(response.message || 'An error occurred.',
                                'error');
                        }
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON?.message ||
                            "{{ __('An error occurred while processing the request.') }}";
                        toastNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        $('#loading-spinner').addClass('hidden').removeClass('flex');
                        currentUserId = null;
                        currentAction = null;
                    }
                });
            });
        });
    </script>
@endsection
