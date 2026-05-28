@extends('templates.bento.blades.admin.layouts.admin')

@section('content')
    <div
        class="bg-secondary/40 border border-white/5 rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col lg:flex-row backdrop-blur-xl min-h-[calc(100vh-160px)]">
        {{-- Settings Sidebar --}}
        <div
            class="w-full lg:w-80 bg-secondary/60 shrink-0 border-b lg:border-b-0 lg:border-r border-white/5 flex flex-col relative group">
            <div
                class="absolute inset-0 bg-gradient-to-b from-accent-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
            </div>
            <div class="relative z-10 flex-1 py-8 px-6 lg:px-8 overflow-y-auto custom-scrollbar">
                @include('templates.bento.blades.admin.settings.partials.sidebar')
            </div>
        </div>

        {{-- Settings Content Area --}}
        <div class="flex-1 flex flex-col pt-10 px-6 md:px-10 lg:px-12 overflow-y-auto custom-scrollbar"
            id="contentScrollContainer">
            <div class="max-w-4xl">
                <div class="flex flex-col gap-2 mb-10 border-b border-white/5 pb-8">
                    <h2 class="text-3xl font-bold text-white tracking-tight leading-none">
                        {{ __('Email Configuration') }}
                    </h2>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">
                        {{ __('Configure SMTP delivery and global notification triggers') }}</p>
                </div>

                <form id="email-settings-form" action="{{ route('admin.settings.email.update') }}" method="POST"
                    class="space-y-12 pb-24 ajax-form">
                    @csrf

                    {{-- Section 1: Delivery Method --}}
                    <section class="space-y-8">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">{{ __('Delivery Driver') }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Mail Driver') }}</label>
                                <select name="mail_driver"
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-sm font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none appearance-none">
                                    <option value="smtp"
                                        {{ ($mail_config['driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('SMTP') }}</option>
                                    <option value="mailgun"
                                        {{ ($mail_config['driver'] ?? '') == 'mailgun' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('Mailgun') }}</option>
                                    <option value="ses" {{ ($mail_config['driver'] ?? '') == 'ses' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('Amazon SES') }}</option>
                                    <option value="sendmail"
                                        {{ ($mail_config['driver'] ?? '') == 'sendmail' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('Sendmail') }}</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- Section 2: SMTP Configuration --}}
                    <section class="space-y-8 pt-8 border-t border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                                {{ __('SMTP Server Settings') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div class="flex flex-col gap-3 lg:col-span-2">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Mail Host') }}</label>
                                <input type="text" name="mail_host"
                                    value="{{ sandBoxCredentials($mail_config['host']) ?? '' }}" required
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Mail Port') }}</label>
                                <input type="number" name="mail_port" value="{{ $mail_config['port'] ?? '' }}" required
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Mail Username') }}</label>
                                <input type="text" name="mail_username"
                                    value="{{ sandBoxCredentials($mail_config['username']) ?? '' }}"
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Mail Password') }}</label>
                                <div class="relative group/pass">
                                    <input type="password" name="mail_password" id="mail_password"
                                        value="{{ sandBoxCredentials($mail_config['password']) ?? '' }}"
                                        class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none pr-14">
                                    <button type="button" id="toggle-password"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-xl bg-white/5 border border-white/10 text-slate-500 hover:text-white hover:bg-white/10 transition-all duration-300">
                                        <svg class="w-5 h-5" id="eye-icon" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('Encryption') }}</label>
                                <select name="mail_encryption"
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none appearance-none">
                                    <option value="" {{ ($mail_config['encryption'] ?? '') == '' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('None') }}</option>
                                    <option value="tls"
                                        {{ ($mail_config['encryption'] ?? '') == 'tls' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('TLS') }}</option>
                                    <option value="ssl"
                                        {{ ($mail_config['encryption'] ?? '') == 'ssl' ? 'selected' : '' }}
                                        class="bg-secondary-dark">{{ __('SSL') }}</option>
                                </select>
                            </div>
                        </div>
                    </section>

                    {{-- Section 3: Sender Identity --}}
                    <section class="space-y-8 pt-8 border-t border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">{{ __('Sender Identity') }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('From Name') }}</label>
                                <input type="text" name="mail_from_name"
                                    value="{{ $mail_config['from_name'] ?? '' }}" required
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>

                            <div class="flex flex-col gap-3">
                                <label
                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ __('From Email') }}</label>
                                <input type="email" name="mail_from_address"
                                    value="{{ sandBoxCredentials($mail_config['from_address']) ?? '' }}" required
                                    class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white text-base font-bold focus:border-accent-primary/50 focus:ring-4 focus:ring-accent-primary/10 transition-all outline-none">
                            </div>
                        </div>
                    </section>

                    {{-- Section 4: Global Notification Toggles --}}
                    <section class="space-y-8 pt-8 border-t border-white/5">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                                {{ __('Notification Triggers') }}</h3>
                        </div>

                        {{-- Global Delivery Features --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div
                                class="p-6 rounded-3xl bg-accent-primary/5 border border-accent-primary/10 flex items-center justify-between group/toggle hover:bg-accent-primary/[0.08] transition-all duration-300">
                                <div class="flex flex-col gap-1 pr-4">
                                    <span
                                        class="text-xs font-bold text-white group-hover/toggle:text-accent-primary transition-colors">{{ __('Email Queue') }}</span>
                                    <p class="text-[10px] text-slate-500 font-bold leading-relaxed">
                                        {{ __('Process emails in the background to improve performance.') }}</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="email_queue" value="enabled" class="sr-only peer"
                                        {{ $email_queue == 'enabled' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-white/5 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-slate-500 after:border-slate-500 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent-primary/20 peer-checked:after:bg-accent-primary peer-checked:after:border-accent-primary border border-white/10 transition-all duration-300">
                                    </div>
                                </label>
                            </div>

                            <div
                                class="p-6 rounded-3xl bg-accent-primary/5 border border-accent-primary/10 flex items-center justify-between group/toggle hover:bg-accent-primary/[0.08] transition-all duration-300">
                                <div class="flex flex-col gap-1 pr-4">
                                    <span
                                        class="text-xs font-bold text-white group-hover/toggle:text-accent-primary transition-colors">{{ __('Date in Subject') }}</span>
                                    <p class="text-[10px] text-slate-500 font-bold leading-relaxed">
                                        {{ __('Automatically append the current date to email subjects.') }}</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="append_date_to_emails" value="enabled"
                                        class="sr-only peer" {{ $append_date == 'enabled' ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-white/5 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-slate-500 after:border-slate-500 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent-primary/20 peer-checked:after:bg-accent-primary peer-checked:after:border-accent-primary border border-white/10 transition-all duration-300">
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($notifications['notifications'] ?? [] as $key => $item)
                                @php
                                    $label_append = str_contains($key, 'email') ? '' : ' Email';
                                    $label = ucwords(str_replace('_', ' ', $key)) . $label_append;
                                @endphp
                                <div
                                    class="p-5 rounded-2xl bg-white/5 border border-white/5 flex items-center justify-between group/toggle hover:bg-white/[0.08] transition-all duration-300">
                                    <div class="flex flex-col gap-1 pr-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-xs font-bold text-white group-hover/toggle:text-accent-primary transition-colors">{{ __($label) }}</span>
                                            @if (!empty($item['warning']))
                                                <div class="group/tip relative">
                                                    <svg class="w-3.5 h-3.5 text-red-500/50 hover:text-red-500 cursor-help transition-colors"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 rounded-lg bg-red-900/90 border border-red-500/20 text-[9px] font-bold text-white opacity-0 pointer-events-none group-hover/tip:opacity-100 transition-opacity z-50 shadow-xl backdrop-blur-md">
                                                        {{ __($item['warning']) }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-slate-500 font-bold leading-relaxed">
                                            @if (!empty($item['warning']))
                                                <span class="text-red-500">{{ __($item['tip'] ?? '') }}</span>
                                            @else
                                                <span>{{ __($item['tip'] ?? '') }}</span>
                                            @endif
                                        </p>
                                    </div>

                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="notifications[{{ $key }}]" value="enabled"
                                            class="sr-only peer"
                                            {{ ($item['status'] ?? 'enabled') == 'enabled' ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-white/5 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-slate-500 after:border-slate-500 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent-primary/20 peer-checked:after:bg-accent-primary peer-checked:after:border-accent-primary border border-white/10 transition-all duration-300">
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Sticky Submit Bar --}}
                    <div class="pt-10 flex flex-col md:flex-row items-center gap-6 border-t border-white/5 mt-10">
                        <button type="submit" id="submit-btn"
                            class="w-full md:w-auto px-12 py-5 bg-accent-primary text-white text-xs font-bold uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-accent-primary/30 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-4">
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                        </button>

                        <div class="h-10 w-px bg-white/5 hidden md:block"></div>

                        <div
                            class="flex-1 w-full flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl p-2 pl-6">
                            <input type="email" id="test-email-input"
                                placeholder="{{ __('Enter email for test...') }}"
                                class="flex-1 bg-transparent border-none text-white text-base font-bold outline-none placeholder:text-slate-600">
                            <button type="button" id="send-test-btn"
                                class="px-6 py-3 bg-white/5 hover:bg-emerald-500/10 hover:text-emerald-500 hover:border-emerald-500/30 border border-white/5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all duration-300">
                                {{ __('Test SMTP') }}
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Email templates list routing to the code editor --}}
                <section class="space-y-8 mt-12 pt-12 border-t border-white/5 pb-24" id="email-templates-section">
                    <div class="flex items-center justify-between cursor-pointer group/header" id="toggle-templates">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-8 h-8 rounded-lg bg-accent-primary/10 flex items-center justify-center text-accent-primary group-hover/header:bg-accent-primary group-hover/header:text-white transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="flex flex-col gap-1">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                                    {{ __('Email Templates') }}
                                </h3>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                                    {{ __('Modify the content and design of automated system emails') }}</p>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-500 group-hover/header:text-white group-hover/header:bg-white/10 transition-all duration-300"
                            id="template-arrow">
                            <svg class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-3 hidden" id="templates-container">
                        @foreach ($email_templates as $template)
                            <div
                                class="p-4 rounded-2xl bg-white/5 border border-white/10 group hover:bg-white/[0.08] hover:border-accent-primary/30 transition-all duration-300 flex items-center justify-between gap-6">
                                <div class="flex items-center gap-5 overflow-hidden">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-accent-primary/10 flex items-center justify-center text-accent-primary border border-accent-primary/20 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="text-xs font-bold text-white group-hover:text-accent-primary transition-colors truncate">{{ $template['name'] }}</span>
                                        <span
                                            class="text-[10px] text-slate-500 font-bold tracking-widest truncate">{{ sandBoxCredentials($template['path']) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('admin.file-manager.code-editor', ['path' => config('app.env') == 'sandbox' ? '' : $template['path']]) }}"
                                    target="_blank"
                                    class="shrink-0 px-6 py-2.5 bg-white/5 hover:bg-accent-primary text-white text-[10px] font-bold uppercase tracking-widest rounded-xl border border-white/5 hover:border-accent-primary transition-all duration-300 flex items-center gap-2 group/btn">
                                    <span>{{ __('Edit') }}</span>
                                    <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            /**
             * Toggle Email Templates Visibility
             */
            $('#toggle-templates').on('click', function() {
                const $container = $('#templates-container');
                const $arrow = $('#template-arrow svg');

                $container.slideToggle(300);
                $arrow.toggleClass('rotate-180');
            });

            /**
             * Toggle Password Visibility
             */
            $('#toggle-password').on('click', function() {
                const $input = $('#mail_password');
                const $icon = $('#eye-icon');
                const type = $input.attr('type') === 'password' ? 'text' : 'password';
                $input.attr('type', type);

                // Toggle Icon Path
                if (type === 'text') {
                    $icon.html(`
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    `);
                } else {
                    $icon.html(`
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    `);
                }
            });

            /**
             * Test Email Logic
             */
            $('#send-test-btn').on('click', function() {
                const $btn = $(this);
                const $input = $('#test-email-input');
                const email = $input.val();
                const originalText = $btn.text();

                if (!email) {
                    toastNotification('{{ __('Please enter a valid email address.') }}', 'warning');
                    return;
                }

                $btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed').text(
                    '{{ __('Sending...') }}');

                $.ajax({
                    url: '{{ route('admin.settings.email.test') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: email
                    },
                    success: function(response) {
                        toastNotification(response.message, 'success');
                    },
                    error: function(xhr) {
                        let msg = '{{ __('Test failed. Check SMTP settings.') }}';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                            .message;
                        toastNotification(msg, 'error');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).removeClass(
                            'opacity-50 cursor-not-allowed').text(originalText);
                    }
                });
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(var(--accent-primary-rgb), 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(var(--accent-primary-rgb), 0.3);
        }

        select option {
            background-color: #1a1a2e;
            color: #fff;
            padding: 10px;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
