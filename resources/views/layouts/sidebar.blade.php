<!-- Sidebar Container -->
<div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="group fixed inset-y-0 left-0 w-72 lg:w-20 lg:hover:w-72 bg-slate-950 border-r border-slate-900/80 backdrop-blur-xl z-50 flex flex-col justify-between transition-all duration-300 ease-in-out -translate-x-full lg:translate-x-0 select-none shadow-2xl hover:shadow-black/80">
    
    <!-- Top Brand & Navigation Block -->
    <div>
        <!-- Brand / Logo Section -->
        <div class="h-20 flex items-center px-5 border-b border-slate-800/80 relative overflow-hidden bg-slate-950/20">
            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-16 h-16 bg-red-600/10 rounded-full blur-xl pointer-events-none"></div>
            
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 shrink-0">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center shadow-lg shadow-red-500/20 font-bold text-white text-lg shrink-0 transform hover:scale-105 transition duration-200">
                    🛡️
                </div>
                <div class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0">
                    <span class="text-sm font-black tracking-wider uppercase text-white block">GuardianNET</span>
                    <span class="text-[9px] text-red-500 font-extrabold uppercase block tracking-widest mt-[-2px]">Operations Panel</span>
                </div>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="px-3.5 py-8 space-y-2">
            <!-- Header Label -->
            <p class="px-3 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4 transition-all duration-300 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 overflow-hidden whitespace-nowrap">Core Systems</p>

            <!-- Dashboard Link -->
            <a href="{{ Auth::user()->role_type === 'Citizen' ? route('citizen.dashboard') : route('dashboard') }}" class="group/link flex items-center px-4 py-3.5 rounded-xl text-xs font-bold tracking-wider uppercase transition duration-150 {{ ((request()->routeIs('citizen.dashboard') || request()->routeIs('dashboard')) && !request('tab')) ? 'bg-gradient-to-r from-red-950/30 to-slate-900/30 border-l-[3px] border-red-500 text-white shadow-lg shadow-red-500/5' : 'text-slate-400 hover:text-white hover:bg-slate-800/40 border-l-[3px] border-transparent' }}">
                <svg class="w-4 h-4 shrink-0 transition duration-150 {{ ((request()->routeIs('citizen.dashboard') || request()->routeIs('dashboard')) && !request('tab')) ? 'text-red-500' : 'text-slate-500 group-hover/link:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                </svg>
                <span class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0 ml-3">Home</span>
            </a>
 
            @if(Auth::user()->role_type === 'Citizen')
                <!-- Edit Medical Profile Link -->
                <a href="{{ route('citizen.dashboard', ['tab' => 'medical']) }}" class="group/link flex items-center px-4 py-3.5 rounded-xl text-xs font-bold tracking-wider uppercase transition duration-150 {{ request('tab') === 'medical' ? 'bg-gradient-to-r from-red-950/30 to-slate-900/30 border-l-[3px] border-red-500 text-white shadow-lg shadow-red-500/5' : 'text-slate-400 hover:text-white hover:bg-slate-800/40 border-l-[3px] border-transparent' }}">
                    <svg class="w-4 h-4 shrink-0 transition duration-150 {{ request('tab') === 'medical' ? 'text-red-500' : 'text-slate-500 group-hover/link:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0 ml-3">Medical Details</span>
                </a>
 
                <!-- Resolved SOS History Link -->
                <a href="{{ route('citizen.dashboard', ['tab' => 'history']) }}" class="group/link flex items-center px-4 py-3.5 rounded-xl text-xs font-bold tracking-wider uppercase transition duration-150 {{ request('tab') === 'history' ? 'bg-gradient-to-r from-red-950/30 to-slate-900/30 border-l-[3px] border-red-500 text-white shadow-lg shadow-red-500/5' : 'text-slate-400 hover:text-white hover:bg-slate-800/40 border-l-[3px] border-transparent' }}">
                    <svg class="w-4 h-4 shrink-0 transition duration-150 {{ request('tab') === 'history' ? 'text-red-500' : 'text-slate-500 group-hover/link:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0 ml-3">Resolved SOS</span>
                </a>

                <!-- Crisis Support & Survival Manual Link -->
                <a href="{{ route('citizen.dashboard', ['tab' => 'manual']) }}" class="group/link flex items-center px-4 py-3.5 rounded-xl text-xs font-bold tracking-wider uppercase transition duration-150 {{ request('tab') === 'manual' ? 'bg-gradient-to-r from-red-950/30 to-slate-900/30 border-l-[3px] border-red-500 text-white shadow-lg shadow-red-500/5' : 'text-slate-400 hover:text-white hover:bg-slate-800/40 border-l-[3px] border-transparent' }}">
                    <svg class="w-4 h-4 shrink-0 transition duration-150 {{ request('tab') === 'manual' ? 'text-red-500' : 'text-slate-500 group-hover/link:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0 ml-3">Survival Manual</span>
                </a>
            @endif

            @if(Auth::user()->role_type === 'Admin')
                <!-- Add First Responder Link -->
                <a href="{{ route('admin.dashboard', ['tab' => 'responders']) }}" class="group/link flex items-center px-4 py-3.5 rounded-xl text-xs font-bold tracking-wider uppercase transition duration-150 {{ request('tab') === 'responders' ? 'bg-gradient-to-r from-red-950/30 to-slate-900/30 border-l-[3px] border-red-500 text-white shadow-lg shadow-red-500/5' : 'text-slate-400 hover:text-white hover:bg-slate-800/40 border-l-[3px] border-transparent' }}">
                    <svg class="w-4 h-4 shrink-0 transition duration-150 {{ request('tab') === 'responders' ? 'text-red-500' : 'text-slate-500 group-hover/link:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <span class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0 ml-3">Manage Responders</span>
                </a>
            @endif

            <!-- Profile Settings Link -->
            <a href="{{ route('profile.edit') }}" class="group/link flex items-center px-4 py-3.5 rounded-xl text-xs font-bold tracking-wider uppercase transition duration-150 {{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-red-950/30 to-slate-900/30 border-l-[3px] border-red-500 text-white shadow-lg shadow-red-500/5' : 'text-slate-400 hover:text-white hover:bg-slate-800/40 border-l-[3px] border-transparent' }}">
                <svg class="w-4 h-4 shrink-0 transition duration-150 {{ request()->routeIs('profile.edit') ? 'text-red-500' : 'text-slate-500 group-hover/link:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0 ml-3">Profile Settings</span>
            </a>
        </div>
    </div>

    <!-- Bottom User profile & Logout block -->
    <div class="p-3.5 border-t border-slate-800/80 bg-slate-950/30">
        <!-- Profile info block -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-3.5 mb-3 flex items-center justify-between overflow-hidden">
            <div class="flex items-center space-x-3 min-w-0 shrink-0">
                <!-- Glowing status dot -->
                <div class="relative flex h-2.5 w-2.5 shrink-0 ml-1">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </div>
                <!-- Name and role -->
                <div class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0">
                    <p class="text-xs font-extrabold text-white truncate">{{ Auth::user()->name }}</p>
                    <span class="text-[9px] font-black uppercase tracking-wider block mt-0.5 {{ Auth::user()->role_type === 'Admin' ? 'text-red-400 bg-red-950/50 px-2 py-0.5 rounded-full border border-red-500/20 max-w-max' : (Auth::user()->role_type === 'Responder' ? 'text-blue-400 bg-blue-950/50 px-2 py-0.5 rounded-full border border-blue-500/20 max-w-max' : 'text-slate-400 bg-slate-950 px-2 py-0.5 rounded-full border border-slate-800 max-w-max') }}">
                        {{ Auth::user()->role_type }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Logout Action Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center justify-center w-full py-3.5 rounded-xl text-xs font-extrabold uppercase tracking-widest text-red-400 hover:text-red-300 bg-red-950/20 hover:bg-red-950/40 border border-red-500/10 hover:border-red-500/30 transition duration-150">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="opacity-100 max-w-xs pointer-events-auto lg:opacity-0 lg:max-w-0 lg:pointer-events-none lg:group-hover:opacity-100 lg:group-hover:max-w-xs lg:group-hover:pointer-events-auto transition-all duration-300 overflow-hidden shrink-0 ml-2">Log Out</span>
            </a>
        </form>
    </div>
</div>

<!-- Mobile Drawer Overlay Background -->
<div x-show="sidebarOpen" style="display: none;" @click="sidebarOpen = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 z-40 lg:hidden backdrop-blur-sm"></div>
