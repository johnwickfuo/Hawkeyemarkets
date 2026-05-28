@extends('templates.bento.blades.layouts.auth')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h3 class="text-2xl font-display font-bold text-white tracking-tight">
                {{ __('Set New Password') }}
            </h3>
            <p class="text-text-secondary text-sm mt-1 mb-6">
                {{ __('Choose a strong password to secure your account.') }}
            </p>
        </div>

        {{-- Form --}}
        <form action="{{ route('user.reset-password.update') }}" method="POST" class="ajax-form space-y-6"
            data-action="redirect">
            @csrf

            {{-- New Password --}}
            <div class="space-y-2">
                <label for="password"
                    class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">
                    {{ __('New Password') }}
                </label>
                <input type="password" name="password" id="password"
                    class="w-full bg-primary-dark/50 border border-white/10 rounded-2xl px-4 py-3.5 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                    placeholder="••••••••" required autofocus>
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-2">
                <label for="password_confirmation"
                    class="block text-xs font-mono font-medium text-text-secondary uppercase tracking-wider">
                    {{ __('Confirm Password') }}
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full bg-primary-dark/50 border border-white/10 rounded-2xl px-4 py-3.5 text-white placeholder-white/20 focus:outline-none focus:border-accent-primary focus:ring-1 focus:ring-accent-primary transition-all font-sans text-base"
                    placeholder="••••••••" required>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full bg-gradient-to-r from-accent-primary to-accent-glow hover:from-accent-glow hover:to-accent-primary text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-accent-primary/20 transition-all duration-300 transform hover:-translate-y-0.5 relative overflow-hidden group cursor-pointer">
                <span class="relative z-10">{{ __('Update Password') }}</span>
                <div class="absolute inset-0 h-full w-full bg-white/20 group-hover:bg-transparent transition-colors"></div>
            </button>
        </form>
    </div>
@endsection
