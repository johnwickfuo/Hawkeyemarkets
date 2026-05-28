@extends('templates.bento.blades.admin.layouts.auth')

@section('content')
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">{{ __('Set New Password') }}</h2>
            <p class="text-slate-400 text-sm mt-1">
                {{ __('Choose a strong password to secure your administrator account.') }}</p>
        </div>

        <form action="{{ route('admin.reset-password.update') }}" method="POST" class="ajax-form space-y-5"
            data-action="redirect">
            @csrf

            {{-- Password --}}
            <div class="space-y-2">
                <label for="password" class="block text-xs font-mono font-medium text-slate-400 uppercase tracking-wider">
                    {{ __('New Password') }}
                </label>
                <input type="password" name="password" id="password"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-sans text-base"
                    placeholder="••••••••" required autofocus>
            </div>

            {{-- Password Confirmation --}}
            <div class="space-y-2">
                <label for="password_confirmation"
                    class="block text-xs font-mono font-medium text-slate-400 uppercase tracking-wider">
                    {{ __('Confirm Password') }}
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-sans text-base"
                    placeholder="••••••••" required>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-500/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                <span class="relative z-10">{{ __('Update Password') }}</span>
                <div class="absolute inset-0 bg-white/10 group-hover:bg-transparent transition-colors"></div>
            </button>
        </form>
    </div>
@endsection
