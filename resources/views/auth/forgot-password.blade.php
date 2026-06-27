<x-guest-layout>
    <div class="mb-6 text-sm text-slate-400 leading-relaxed">
        {{ __('Forgot your password? No problem. Just enter the email address linked to your GuardianNET profile and we will dispatch a secure reset link.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Emergency Contact Email')" class="text-slate-300 font-bold uppercase tracking-wider text-xs mb-2" />
            <x-text-input id="email" class="block mt-1 w-full bg-slate-900 border-slate-700 text-white focus:border-red-500 focus:ring-red-500 rounded-xl" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 font-bold" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a href="{{ route('login') }}" class="text-xs font-bold text-slate-500 hover:text-slate-300 uppercase tracking-widest transition duration-200">
                &larr; Return to Login
            </a>
            
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-600 to-amber-500 hover:from-red-500 hover:to-amber-400 text-white font-extrabold rounded-xl shadow-lg shadow-red-500/20 transform transition duration-200 hover:scale-[1.02]">
                {{ __('Dispatch Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
