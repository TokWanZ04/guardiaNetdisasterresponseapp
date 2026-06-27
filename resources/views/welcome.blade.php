<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GuardianNET - Emergency Disaster Response System</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .beacon-glow-red {
                box-shadow: 0 0 80px 20px rgba(239, 68, 68, 0.15);
            }
            .beacon-glow-blue {
                box-shadow: 0 0 80px 20px rgba(59, 130, 246, 0.15);
            }
        </style>
    </head>
    <body class="bg-slate-950 font-sans text-slate-100 antialiased selection:bg-red-500 selection:text-white min-h-screen relative overflow-x-hidden">
        <!-- Glowing Beacons in background -->
        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] rounded-full beacon-glow-red bg-red-600/5 blur-[120px] pointer-events-none hidden md:block"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full beacon-glow-blue bg-blue-600/5 blur-[120px] pointer-events-none hidden md:block"></div>

        <div class="max-w-7xl mx-auto px-6 py-8 relative z-10 flex flex-col min-h-screen justify-between">
            <!-- Header Nav -->
            <header class="flex justify-between items-center pb-6 border-b border-slate-800/80">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center shadow-lg shadow-red-500/20 font-extrabold text-white text-lg">
                        🛡️
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-wider uppercase text-white bg-clip-text bg-gradient-to-r from-white via-slate-100 to-slate-400">GuardianNET</span>
                        <span class="text-[9px] text-red-500 font-extrabold uppercase block tracking-widest mt-[-2px]">Disaster System</span>
                    </div>
                </div>

                <div>
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ Auth::user()->role_type === 'Citizen' ? route('citizen.dashboard') : (Auth::user()->role_type === 'Responder' ? route('responder.dashboard') : route('admin.dashboard')) }}" class="px-5 py-2 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-red-600 to-amber-500 hover:from-red-500 hover:to-amber-400 shadow-md shadow-red-500/10 transition-all duration-200">
                                    Access Console
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-300 hover:text-white transition">Log In</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-slate-900 border border-slate-800 hover:bg-slate-800 transition">
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </header>

            <!-- Hero Section -->
            <main class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center my-auto py-12">
                <div class="lg:col-span-7 space-y-6 text-left">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-red-950/80 text-red-400 border border-red-800/40 uppercase tracking-widest animate-pulse">
                        🔴 LIVE BROADCAST & EMERGENCY SERVICES OPERATIONAL
                    </span>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.1]">
                        When Seconds Count.<br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-500 via-amber-400 to-red-600">GuardianNET</span> Protects.
                    </h1>

                    <p class="text-slate-400 text-lg leading-relaxed max-w-xl">
                        A military-grade, real-time crisis coordination network connecting citizens in distress directly with emergency first responders, featuring live GPS tracking and offline first-aid guides.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
                        <a href="{{ route('login') }}" class="px-8 py-4 rounded-2xl text-base font-extrabold text-white bg-gradient-to-r from-red-600 to-amber-500 hover:from-red-500 hover:to-amber-400 shadow-xl shadow-red-500/20 text-center transition duration-200 transform hover:scale-[1.02]">
                            🚨 Trigger SOS / Log In
                        </a>
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-2xl text-base font-extrabold text-slate-300 bg-slate-900 border border-slate-800 hover:border-slate-700 hover:bg-slate-800 text-center transition duration-200">
                            Register Citizen Profile
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-5 grid grid-cols-1 gap-4">
                    <!-- Feature Card 1 -->
                    <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 p-6 rounded-3xl shadow-xl flex items-start space-x-4 hover:border-red-500/30 transition duration-200">
                        <div class="h-12 w-12 rounded-2xl bg-red-950/80 border border-red-800/40 flex items-center justify-center text-red-400 shrink-0 text-xl font-bold">
                            🆘
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white uppercase tracking-wide">Instant SOS Broadcast</h3>
                            <p class="text-slate-400 text-xs mt-1 leading-relaxed">Pinpoint GPS distress signals immediately dispatched to active first-responders with automatic triage profiles.</p>
                        </div>
                    </div>

                    <!-- Feature Card 2 -->
                    <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 p-6 rounded-3xl shadow-xl flex items-start space-x-4 hover:border-blue-500/30 transition duration-200">
                        <div class="h-12 w-12 rounded-2xl bg-blue-950/80 border border-blue-800/40 flex items-center justify-center text-blue-400 shrink-0 text-xl font-bold">
                            📍
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white uppercase tracking-wide">Live Dispatch Map Tracking</h3>
                            <p class="text-slate-400 text-xs mt-1 leading-relaxed">Watch your rescue officer crawl directly to your coordinates in real-time, fitted dynamically with CSS vehicle animations.</p>
                        </div>
                    </div>

                    <!-- Feature Card 3 -->
                    <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800/80 p-6 rounded-3xl shadow-xl flex items-start space-x-4 hover:border-emerald-500/30 transition duration-200">
                        <div class="h-12 w-12 rounded-2xl bg-emerald-950/80 border border-emerald-800/40 flex items-center justify-center text-emerald-400 shrink-0 text-xl font-bold">
                            🩺
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white uppercase tracking-wide">Offline Survival manual</h3>
                            <p class="text-slate-400 text-xs mt-1 leading-relaxed">Access full medical, flood, and fire manual instructions client-side—functional even if internet drops.</p>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="pt-8 border-t border-slate-850/80 flex flex-col sm:flex-row justify-between items-center text-slate-500 text-xs">
                <div>
                    &copy; 2026 GuardianNET Emergency Network. All Rights Secured.
                </div>
                <div class="mt-2 sm:mt-0 flex items-center space-x-2 font-bold text-slate-400">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>System Online</span>
                </div>
            </footer>
        </div>
    </body>
</html>
