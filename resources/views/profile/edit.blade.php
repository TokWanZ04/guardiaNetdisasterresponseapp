<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-100 leading-tight flex items-center space-x-2">
                <span>⚙️</span>
                <span>{{ __('Profile Settings') }}</span>
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs font-extrabold uppercase tracking-wider rounded-xl text-slate-300 hover:text-white transition duration-150 shadow-md">
                ⬅️ Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 relative z-10">
        
        <!-- Profile Info Card -->
        <div class="p-6 sm:p-8 bg-slate-900/60 backdrop-blur-md border border-slate-800 rounded-3xl shadow-2xl">
            <div class="max-w-xl">
                <div class="text-slate-100">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Password Card -->
        <div class="p-6 sm:p-8 bg-slate-900/60 backdrop-blur-md border border-slate-800 rounded-3xl shadow-2xl">
            <div class="max-w-xl">
                <div class="text-slate-100">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete Account Card -->
        <div class="p-6 sm:p-8 bg-slate-900/60 backdrop-blur-md border border-slate-800 rounded-3xl shadow-2xl">
            <div class="max-w-xl">
                <div class="text-slate-100">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
        
    </div>

    <!-- Confirm User Deletion Modal (Placed at root level to prevent backdrop-filter containing block constraints) -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-slate-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 bg-slate-950 border-slate-800 text-slate-100 rounded-xl focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')" class="bg-slate-800 hover:bg-slate-700 text-slate-300 border-slate-700">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
