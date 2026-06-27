<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>GuardianNET - Secure Emergency Access</title>

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
            /* Override standard input styles to match dark premium theme */
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
            }
            button[type="submit"] {
                background: linear-gradient(135deg, #dc2626 0%, #f59e0b 100%) !important;
                color: #ffffff !important;
                font-weight: 800 !important;
                border-radius: 0.75rem !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                transition: all 0.2s ease-in-out !important;
                border: none !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0.625rem 1.25rem !important;
            }
            button[type="submit"]:hover {
                opacity: 0.95 !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3) !important;
            }
            a {
                transition: all 0.2s ease-in-out !important;
            }
            /* Styling standard Breeze guest slots */
            .text-gray-600 {
                color: #94a3b8 !important;
            }
            .text-gray-600:hover {
                color: #ffffff !important;
            }
            .bg-white {
                background-color: rgba(15, 23, 42, 0.6) !important;
            }
        </style>
    </head>
    <body class="bg-slate-950 font-sans text-slate-100 antialiased selection:bg-red-500 selection:text-white min-h-screen relative overflow-x-hidden flex items-center justify-center py-12 px-6">
        <!-- Glowing Beacons in background -->
        <div class="absolute top-[-10%] left-[-10%] w-[40vw] h-[40vw] rounded-full beacon-glow-red bg-red-600/5 blur-[120px] pointer-events-none hidden md:block"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40vw] h-[40vw] rounded-full beacon-glow-blue bg-blue-600/5 blur-[120px] pointer-events-none hidden md:block"></div>

        <div class="w-full max-w-md relative z-10">
            <!-- Branding Header -->
            <div class="flex flex-col items-center justify-center mb-8">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center shadow-lg shadow-red-500/20 font-extrabold text-white text-xl transform group-hover:scale-105 transition duration-200">
                        🛡️
                    </div>
                    <div>
                        <span class="text-xl font-black tracking-wider uppercase text-white">GuardianNET</span>
                        <span class="text-[9px] text-red-500 font-extrabold uppercase block tracking-widest mt-[-2px]">Disaster System</span>
                    </div>
                </a>
            </div>

            <!-- Glassmorphic Form Card -->
            <div class="bg-slate-900 md:bg-slate-900/60 md:backdrop-blur-md border border-slate-800 p-8 rounded-3xl shadow-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
