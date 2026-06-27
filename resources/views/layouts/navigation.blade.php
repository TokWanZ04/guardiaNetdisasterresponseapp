<nav x-data="{ open: false }" class="bg-slate-900/60 border-b border-slate-800 md:backdrop-blur-md relative z-[100]">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-2 group">
                        <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center shadow-md shadow-red-500/20 font-bold text-white text-base transform group-hover:scale-105 transition duration-150">
                            🛡️
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-sm font-black tracking-wider uppercase text-white">GuardianNET</span>
                            <span class="text-[8px] text-red-500 font-extrabold uppercase block tracking-widest mt-[-3px]">Console</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-slate-300 hover:text-white hover:border-slate-350 focus:text-white transition duration-150">
                        {{ __('Operations Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-slate-900 border border-slate-800 rounded-xl overflow-hidden shadow-2xl">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-slate-800 text-sm leading-4 font-bold rounded-xl text-slate-300 bg-slate-950/80 hover:text-white hover:bg-slate-900 focus:outline-none transition ease-in-out duration-150">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                            <div>{{ Auth::user()->name }} <span class="text-[10px] text-slate-500 font-normal">({{ Auth::user()->role_type }})</span></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-slate-300 hover:bg-slate-800 hover:text-white transition py-2 text-xs font-bold uppercase tracking-wider">
                            ⚙️ {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-red-400 hover:bg-red-950/40 hover:text-red-300 transition py-2 text-xs font-bold uppercase tracking-wider border-t border-slate-800/80">
                                🚪 {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-slate-300 hover:text-white">
                {{ __('Operations Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-850">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-slate-300">
                    ⚙️ {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-red-400">
                        🚪 {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
