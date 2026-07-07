<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-[30%] text-slate-400 hover:text-white transition duration-150 focus:outline-none text-sm">
                    👁️
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <script>
            function togglePasswordVisibility(inputId, button) {
                const input = document.getElementById(inputId);
                if (input.type === 'password') {
                    input.type = 'text';
                    button.innerText = '🙈';
                } else {
                    input.type = 'password';
                    button.innerText = '👁️';
                }
            }
        </script>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between mt-8 gap-4">
            @if (Route::has('password.request'))
                <a class="text-xs font-bold text-slate-500 hover:text-slate-300 uppercase tracking-widest transition duration-200" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <div class="flex items-center space-x-3 w-full sm:w-auto">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="flex-1 sm:flex-none text-center px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-extrabold rounded-xl text-xs uppercase tracking-widest transition duration-200 border border-slate-700">
                        {{ __('Register') }}
                    </a>
                @endif

                <x-primary-button class="flex-1 sm:flex-none">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
