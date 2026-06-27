<x-guest-layout>
    <div class="mb-6 text-sm text-slate-400 leading-relaxed text-center">
        <h2 class="text-xl font-black text-white uppercase tracking-widest mb-2">Secure Reset</h2>
        Enter your new password below.
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-slate-300 font-bold uppercase tracking-wider text-xs mb-2" />
            <x-text-input id="email" class="block mt-1 w-full bg-slate-900 border-slate-700 text-white focus:border-red-500 focus:ring-red-500 rounded-xl" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 font-bold" />
        </div>

        <!-- Password -->
        <div class="mt-6">
            <x-input-label for="password" :value="__('New Password')" class="text-slate-300 font-bold uppercase tracking-wider text-xs mb-2" />
            <x-text-input id="password" class="block mt-1 w-full bg-slate-900 border-slate-700 text-white focus:border-red-500 focus:ring-red-500 rounded-xl" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 font-bold" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-6">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-slate-300 font-bold uppercase tracking-wider text-xs mb-2" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-slate-900 border-slate-700 text-white focus:border-red-500 focus:ring-red-500 rounded-xl" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 font-bold" />
        </div>

        <div class="flex items-center justify-end mt-8">
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-amber-500 hover:from-red-500 hover:to-amber-400 text-white font-extrabold rounded-xl shadow-lg shadow-red-500/20 transform transition duration-200 hover:scale-[1.02]">
                {{ __('Reset Password & Login') }}
            </button>
        </div>
    </form>
</x-guest-layout>
