<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>



        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-[30%] text-slate-400 hover:text-white transition duration-150 focus:outline-none text-sm">
                    👁️
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute right-3 top-1/2 -translate-y-[30%] text-slate-400 hover:text-white transition duration-150 focus:outline-none text-sm">
                    👁️
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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

        <div class="flex flex-col sm:flex-row items-center justify-end mt-8 gap-4">
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <a href="{{ route('login') }}" class="flex-1 sm:flex-none text-center px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-extrabold rounded-xl text-xs uppercase tracking-widest transition duration-200 border border-slate-700">
                    {{ __('Log in') }}
                </a>

                <x-primary-button class="flex-1 sm:flex-none">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
