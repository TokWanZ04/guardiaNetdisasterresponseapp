<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>GuardianNET - Secure Operations Console</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif'],
                        }
                    }
                }
            }
        </script>
        
        <style>
            .beacon-glow-red {
                box-shadow: 0 0 80px 20px rgba(239, 68, 68, 0.12);
            }
            .beacon-glow-blue {
                box-shadow: 0 0 80px 20px rgba(59, 130, 246, 0.12);
            }
            /* Override standard input styles to match dark premium theme across panels */
            input[type="text"], input[type="email"], input[type="password"], select {
                background-color: rgba(15, 23, 42, 0.8) !important;
                border-color: rgba(51, 65, 85, 0.6) !important;
                color: #f1f5f9 !important;
                border-radius: 0.75rem !important;
                font-size: 0.875rem !important;
                padding: 0.625rem 0.875rem !important;
            }
            input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus {
                border-color: rgba(239, 68, 68, 0.6) !important;
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
                outline: none !important;
            }
            label {
                color: #94a3b8 !important;
                font-weight: 600 !important;
                font-size: 0.75rem !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                display: block !important;
                margin-bottom: 0.5rem !important;
            }
            .text-gray-900 {
                color: #f1f5f9 !important;
            }
            .text-gray-600 {
                color: #94a3b8 !important;
            }
            .bg-white {
                background-color: rgba(15, 23, 42, 0.6) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen relative overflow-x-hidden">
        <!-- Glowing Beacons in background -->
        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] rounded-full beacon-glow-red bg-red-600/5 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full beacon-glow-blue bg-blue-600/5 blur-[120px] pointer-events-none"></div>

        <!-- Dashboard Wrapper -->
        <div class="min-h-screen flex flex-col lg:flex-row relative z-10" x-data="{ sidebarOpen: false }">
            <!-- Sidebar Navigation -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen lg:pl-20">
                <!-- Mobile Header Top-Bar -->
                <div class="lg:hidden bg-slate-900/80 border-b border-slate-800 backdrop-blur-md py-4 px-6 flex items-center justify-between sticky top-0 z-30">
                    <a href="{{ Auth::user()->role_type === 'Citizen' ? route('citizen.dashboard') : route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="h-8 w-8 rounded-lg bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center font-bold text-white text-sm">
                            🛡️
                        </div>
                        <div>
                            <span class="text-xs font-black tracking-wider uppercase text-white">GuardianNET</span>
                        </div>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white transition duration-150">
                        <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-slate-900/40 border-b border-slate-800/80 backdrop-blur-md py-6 px-6 sm:px-8">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="py-8 px-4 sm:px-6 lg:px-8 flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
