<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight flex flex-col md:flex-row md:items-center justify-between gap-4">
            <span class="flex items-center space-x-2 shrink-0">
                <span class="text-red-500">🛡️</span>
                <span>{{ __('Citizen Portal') }}</span>
            </span>

            <!-- Tactical LED Sign Board Marquee -->
            @if($alerts->isNotEmpty())
            <div class="flex-1 max-w-xl lg:max-w-2xl mx-0 md:mx-6 bg-slate-950 border border-amber-500/20 rounded-xl px-4 h-9 overflow-hidden relative flex items-center self-center shadow-[inset_0_0_10px_rgba(0,0,0,0.8)]">
                <!-- LED Active blink indicator: absolutely centered vertically -->
                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 flex h-2 w-2 z-10">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.8)]"></span>
                </span>
                
                <!-- Ticker Track with strict flex alignment -->
                <div class="w-full overflow-hidden select-none pl-6 relative flex items-center h-full">
                    <div class="whitespace-nowrap inline-flex items-center animate-led-marquee font-mono text-[10px] lg:text-[11px] font-black tracking-widest text-amber-500 drop-shadow-[0_0_3px_rgba(245,158,11,0.5)] h-full">
                        @foreach($alerts as $alert)
                            <span class="inline-flex items-center space-x-1.5 mr-6 py-1 h-full self-center">
                                <span class="text-amber-500/90 text-xs">⚠️</span>
                                <span class="leading-none py-0.5">ALERT [{{ $alert->created_at->format('H:i') }}]: {{ strtoupper($alert->message) }}</span>
                                <span class="text-amber-600/50 text-[10px] pl-4 select-none">•</span>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <span class="text-sm font-normal text-slate-400 bg-slate-900/60 md:backdrop-blur-md px-3 py-1 rounded-full border border-slate-700 shrink-0 self-center">
                Logged in as: <strong class="text-white">{{ auth()->user()->name }}</strong>
            </span>
        </h2>
        <!-- Leaflet.js GPS Tracking CDNs -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        
        <style>
            @keyframes led-marquee {
                0% { transform: translateX(100%); }
                100% { transform: translateX(-100%); }
            }
            .animate-led-marquee {
                animation: led-marquee 35s linear infinite;
            }
            .animate-led-marquee:hover {
                animation-play-state: paused;
                cursor: help;
            }
        </style>
    </x-slot>

    @php
        $tab = request('tab', 'home');
    @endphp

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8 relative z-10">
        
        @if (session('success'))
            <div class="bg-emerald-900/30 border border-emerald-500/30 p-4 rounded-2xl shadow-lg shadow-emerald-500/10 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-emerald-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-bold text-emerald-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if ($tab === 'home')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: SOS & Announcements (Span 2) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Advanced SOS Panel -->
                <div class="bg-slate-900/60 md:backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 transition duration-300 hover:border-red-500/30">
                    <div class="p-8 text-center relative overflow-hidden">
                        <!-- Premium Background Decorative Element -->
                        <div class="absolute top-0 right-0 -mt-12 -mr-12 w-48 h-48 bg-red-600/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-64 h-64 bg-slate-800/30 rounded-full blur-2xl pointer-events-none"></div>

                        <h3 class="text-2xl font-black text-white tracking-tight mb-2">EMERGENCY DISTRESS SIGNAL</h3>
                        <p class="text-slate-400 max-w-md mx-auto mb-8 text-sm">
                            Activating the SOS will instantly pinpoint your GPS coordinates and broadcast an emergency alert to active first responders.
                        </p>
                        
                        <form action="{{ route('citizen.sos') }}" method="POST">
                            @csrf
                            <input type="hidden" name="location" id="location" value="Fetching location...">
                            
                            <div class="mb-8 max-w-xs mx-auto">
                                <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Select Emergency Nature</label>
                                <select id="type" name="type" class="block w-full border-slate-700 bg-slate-800/80 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 text-sm font-bold py-2.5 px-4 text-white placeholder-slate-400">
                                    <option value="Medical">🩺 Medical Emergency</option>
                                    <option value="Fire">🔥 Fire Incident</option>
                                    <option value="Flood">🌊 Flood / Natural Disaster</option>
                                </select>
                            </div>

                            <!-- Pulsing Emergency Button -->
                            <div class="relative inline-flex mb-6 group">
                                @if($activeIncidents->isEmpty())
                                    <span class="animate-ping absolute inline-flex h-36 w-36 rounded-full bg-red-500 opacity-20"></span>
                                    <span class="animate-pulse absolute inline-flex h-36 w-36 rounded-full bg-red-600 opacity-20"></span>
                                    <button type="submit" class="relative z-10 bg-gradient-to-br from-red-600 to-red-800 hover:from-red-500 hover:to-red-700 text-white font-extrabold h-36 w-36 rounded-full shadow-2xl shadow-red-600/30 flex flex-col items-center justify-center border-[6px] border-slate-900 transition-all duration-300 transform hover:scale-105 active:scale-95 group-hover:border-red-950">
                                        <svg class="h-10 w-10 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="text-2xl tracking-wider font-black">SOS</span>
                                    </button>
                                @else
                                    <button type="button" disabled class="relative z-10 bg-slate-800 text-slate-500 font-extrabold h-36 w-36 rounded-full flex flex-col items-center justify-center border-[6px] border-slate-900 cursor-not-allowed shadow-inner">
                                        <svg class="h-8 w-8 mb-1 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span class="text-sm tracking-wider font-black">ACTIVE</span>
                                    </button>
                                @endif
                            </div>

                            <!-- Live Coordinates Indicator -->
                            <div class="flex items-center justify-center space-x-2 text-xs font-bold text-slate-400 mt-2 bg-slate-900/80 rounded-full py-2 px-4 max-w-max mx-auto border border-slate-700">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <span>GPS:</span>
                                <span id="coordinates-text" class="text-emerald-400 font-mono">Scanning...</span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Live SOS Dispatch Tracking -->
                <div class="bg-slate-900/60 md:backdrop-blur-md shadow-2xl rounded-3xl border border-slate-800 overflow-hidden">
                    <div class="p-6 border-b border-slate-800 bg-slate-800/30 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <svg class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            Active SOS Dispatch Tracker
                        </h3>
                        <span class="text-xs font-bold text-slate-500">Live Status Updates</span>
                    </div>
                    <div class="p-6">
                        <div id="active-incidents-container">
                            @include('citizen.partials.active-incidents')
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Premium Emergency Profile (Span 1) -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900/60 md:backdrop-blur-md shadow-2xl rounded-3xl border border-slate-800 overflow-hidden sticky top-8 transition duration-300 hover:border-slate-700">
                    
                    <!-- Header with dynamic styling -->
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 border-b border-slate-700/80 p-6 relative">
                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm z-0 pointer-events-none"></div>
                        <div class="relative z-10">
                            <h3 class="text-lg font-extrabold flex items-center text-white">
                                <svg class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Emergency Medical Profile
                            </h3>
                            <p class="text-slate-400 text-xs mt-1 font-medium">Information used by first responders during medical emergencies.</p>
                            
                        </div>
                    </div>

                    <!-- Tab Content 1: Overview Profile Display -->
                    <div id="profile-tab-view" class="p-6 space-y-6 bg-slate-900/40">
                        
                        <!-- Highlight Box (Blood Type & PWD Status) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-red-900/20 border border-red-500/20 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                                <span class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-2">Blood Type</span>
                                @if(auth()->user()->blood_type)
                                    <span class="text-3xl font-black text-red-500 font-mono drop-shadow-md">{{ auth()->user()->blood_type }}</span>
                                @else
                                    <span class="text-sm font-bold text-red-400/50 italic">Not set</span>
                                @endif
                            </div>
                            <div class="bg-indigo-900/20 border border-indigo-500/20 rounded-2xl p-4 flex flex-col items-center justify-center text-center">
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">PWD Status</span>
                                @if(auth()->user()->is_pwd)
                                    <span class="text-[10px] font-black bg-indigo-600 text-white py-1 px-3 rounded-full mt-1.5 shadow-sm">PERSON WITH DISABILITY</span>
                                @else
                                    <span class="text-sm font-extrabold text-slate-500 mt-2">Standard</span>
                                @endif
                            </div>
                        </div>

                        <!-- Height / Weight Badges -->
                        <div class="grid grid-cols-2 gap-4 border-b border-slate-800 pb-5">
                            <div class="flex items-center space-x-3 bg-slate-800/40 p-3.5 rounded-2xl border border-slate-700/50">
                                <div class="p-2 bg-slate-800 rounded-lg text-slate-400 border border-slate-600/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Height</span>
                                    <p class="text-sm font-extrabold text-white">
                                        {{ auth()->user()->height ? auth()->user()->height . ' cm' : '—' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3 bg-slate-800/40 p-3.5 rounded-2xl border border-slate-700/50">
                                <div class="p-2 bg-slate-800 rounded-lg text-slate-400 border border-slate-600/50">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Weight</span>
                                    <p class="text-sm font-extrabold text-white">
                                        {{ auth()->user()->weight ? auth()->user()->weight . ' kg' : '—' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Diseases / Conditions list -->
                        <div>
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">Diseases & Medical Conditions</h4>
                            @if(auth()->user()->diseases)
                                <div class="bg-amber-900/10 rounded-2xl p-4 border border-amber-500/20">
                                    <p class="text-sm text-slate-300 font-semibold leading-relaxed">
                                        {{ auth()->user()->diseases }}
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-5 bg-slate-800/30 rounded-2xl border border-dashed border-slate-700">
                                    <span class="text-xs text-slate-500 italic font-medium">No medical conditions declared</span>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    @elseif ($tab === 'medical')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form Card (Span 2) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-slate-900/60 md:backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 p-8">
                    <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-slate-850">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-red-600 to-amber-500 flex items-center justify-center font-bold text-white text-lg">
                            🩺
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white uppercase tracking-tight">Update Emergency Medical Profile</h3>
                            <p class="text-slate-400 text-xs mt-0.5">Please provide accurate parameters to expedite responder first-aid operations.</p>
                        </div>
                    </div>

                    <form action="{{ route('citizen.profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="blood_type" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Blood Type</label>
                            <select id="blood_type" name="blood_type" class="block w-full border-slate-700 bg-slate-800 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 text-sm font-bold text-white py-2.5 px-4">
                                <option value="">Select Blood Type</option>
                                <option value="A+" {{ auth()->user()->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ auth()->user()->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ auth()->user()->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ auth()->user()->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ auth()->user()->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ auth()->user()->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ auth()->user()->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ auth()->user()->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="height" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Height (cm)</label>
                                <input type="text" id="height" name="height" value="{{ old('height', auth()->user()->height) }}" placeholder="175" class="block w-full border-slate-700 bg-slate-800 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 text-sm font-bold text-white py-2.5 px-4 placeholder-slate-500">
                            </div>
                            <div>
                                <label for="weight" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Weight (kg)</label>
                                <input type="text" id="weight" name="weight" value="{{ old('weight', auth()->user()->weight) }}" placeholder="70" class="block w-full border-slate-700 bg-slate-800 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 text-sm font-bold text-white py-2.5 px-4 placeholder-slate-500">
                            </div>
                        </div>

                        <div>
                            <label for="diseases" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Medical Conditions / Critical Allergies</label>
                            <textarea id="diseases" name="diseases" rows="4" placeholder="Asthma, Type-1 Diabetes, Cardiovascular conditions, drug allergies, penicillin sensitivity, etc." class="block w-full border-slate-700 bg-slate-800 rounded-xl shadow-sm focus:ring-red-500 focus:border-red-500 text-sm font-bold text-white py-2.5 px-4 placeholder-slate-500">{{ old('diseases', auth()->user()->diseases) }}</textarea>
                        </div>

                        <div class="bg-slate-800/40 p-5 rounded-2xl border border-slate-700/60 flex items-start space-x-4">
                            <input type="checkbox" id="is_pwd" name="is_pwd" value="1" {{ auth()->user()->is_pwd ? 'checked' : '' }} class="h-5 w-5 text-red-650 bg-slate-900 border-slate-650 rounded focus:ring-red-500 mt-0.5">
                            <div class="flex-1">
                                <label for="is_pwd" class="block text-xs font-black text-white uppercase tracking-wider mb-1">Person with Disability (PWD)</label>
                                <span class="text-slate-400 text-xs leading-relaxed block">Check this box if you have a physical, cognitive, or sensory impairment. First responders will receive automatic alert tags during dispatches to prioritize specialized rescue protocols.</span>
                            </div>
                        </div>

                        <div class="flex space-x-4 pt-4">
                            <a href="{{ route('dashboard') }}" class="flex-1 text-center bg-slate-800 hover:bg-slate-700 text-white border border-slate-600 text-xs font-black uppercase tracking-wider py-4 rounded-xl transition duration-150">
                                Cancel & Back
                            </a>
                            <button type="submit" class="flex-1 text-center bg-gradient-to-r from-red-600 to-amber-600 hover:from-red-500 hover:to-amber-500 text-white text-xs font-black uppercase tracking-wider py-4 rounded-xl shadow-lg shadow-red-500/10 transition duration-150">
                                Save Medical Parameters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Side Card (Span 1) -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900/60 md:backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 p-6 space-y-6">
                    <h3 class="text-base font-black text-white uppercase tracking-wider flex items-center">
                        🛡️ Secure Data Node
                    </h3>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Your health profiles are encrypted and hosted locally on the <strong>GuardianNET Operations Mesh</strong>.
                    </p>
                    <div class="bg-slate-950/40 border border-slate-800 p-4 rounded-2xl space-y-3">
                        <div class="flex items-center space-x-2 text-xs font-bold text-red-400">
                            <span>🚨</span>
                            <span>Why keep this updated?</span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">
                            During severe floods or fires, first responders retrieve your blood type and PWD tags instantly to deploy suitable transport units and specialized medical officers.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    @elseif ($tab === 'history')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- History Card List (Span 2) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-slate-900/60 md:backdrop-blur-md shadow-2xl rounded-3xl border border-slate-800 overflow-hidden">
                    <div class="p-6 border-b border-slate-800 bg-slate-800/30 flex items-center justify-between">
                        <h3 class="text-base font-extrabold text-slate-200 flex items-center uppercase tracking-wider">
                            <svg class="h-5 w-5 mr-2 text-slate-450" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Resolved SOS History Log
                        </h3>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Archive</span>
                    </div>
                    <div class="p-6">
                        @if($resolvedIncidents->isEmpty())
                            <div class="text-center py-10">
                                <div class="bg-slate-800 h-12 w-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="h-6 w-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-400">No past resolved incidents recorded.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($resolvedIncidents as $incident)
                                    <div class="bg-slate-950/40 border border-slate-800 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs hover:bg-slate-950/60 transition duration-150">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <span class="font-black text-slate-200 text-sm">
                                                    @if($incident->type === 'Medical') 🩺 Medical Emergency @elseif($incident->type === 'Fire') 🔥 Fire Incident @else 🌊 Flood Rescue @endif
                                                </span>
                                                <span class="text-[9px] font-bold text-slate-500 font-mono bg-slate-900 px-2 py-0.5 rounded-full">#{{ $incident->id }}</span>
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-2 font-semibold flex items-center">
                                                <span class="text-slate-500 mr-1.5">📍</span> Location: {{ $incident->location }}
                                            </p>
                                            <p class="text-[9px] text-slate-500 mt-1 font-bold">Resolved {{ $incident->updated_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="text-left sm:text-right">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-slate-900 text-emerald-400 border border-emerald-500/10">
                                                Resolved
                                            </span>
                                            @php
                                                $assignedLog = $incident->responseLogs->last();
                                            @endphp
                                            @if($assignedLog && $assignedLog->responder)
                                                <p class="text-[10px] font-extrabold text-slate-400 mt-2 flex items-center justify-end">
                                                    👤 Officer: {{ $assignedLog->responder->name }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Column (Span 1) -->
            <div class="lg:col-span-1">
                <div class="bg-slate-900/60 md:backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 p-6 space-y-6">
                    <h3 class="text-base font-black text-white uppercase tracking-wider flex items-center">
                        📊 Operations Stats
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-slate-950/40 border border-slate-800 p-4 rounded-xl">
                            <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Total SOS Resolved</span>
                            <p class="text-2xl font-black text-emerald-500 mt-1">{{ $resolvedIncidents->count() }} Cases</p>
                        </div>
                        <div class="bg-slate-950/40 border border-slate-800 p-4 rounded-xl">
                            <span class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">System Status</span>
                            <p class="text-xs font-extrabold text-white mt-1.5 flex items-center">
                                <span class="h-2 w-2 rounded-full bg-emerald-500 mr-2 animate-ping"></span>
                                Fully Operational Node
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif ($tab === 'manual')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Manual Content Panel (Span 2) -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-slate-900/60 md:backdrop-blur-md shadow-2xl rounded-3xl border border-slate-800 overflow-hidden">
                    <div class="p-6 border-b border-slate-800 bg-slate-800/30 flex items-center justify-between">
                        <h3 class="text-base font-extrabold text-white flex items-center uppercase tracking-wider">
                            <svg class="h-5 w-5 mr-3 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Crisis Support & Survival Manual
                        </h3>
                        <span class="text-xs font-bold text-red-500 bg-red-950/40 px-3 py-1 rounded-full border border-red-500/10 uppercase tracking-widest font-mono">Offline Access Enabled</span>
                    </div>
                    <div class="p-6">
                        <!-- Tab Header Buttons -->
                        <div class="flex border border-slate-800/80 mb-6 bg-slate-950/80 p-1.5 rounded-2xl">
                            <button type="button" onclick="switchManualTab('flood')" id="tab-btn-flood" class="flex-1 text-center py-3 text-xs font-black uppercase tracking-wider rounded-xl bg-slate-850 text-white border border-slate-700 shadow-sm transition duration-150">🌊 Flood Guide</button>
                            <button type="button" onclick="switchManualTab('fire')" id="tab-btn-fire" class="flex-1 text-center py-3 text-xs font-black uppercase tracking-wider rounded-xl text-slate-500 hover:text-white hover:bg-slate-850/50 transition duration-150">🔥 Fire Guide</button>
                            <button type="button" onclick="switchManualTab('cpr')" id="tab-btn-cpr" class="flex-1 text-center py-3 text-xs font-black uppercase tracking-wider rounded-xl text-slate-500 hover:text-white hover:bg-slate-850/50 transition duration-150">🩺 Medical (CPR)</button>
                        </div>

                        <!-- Tab Contents -->
                        <!-- FLOOD TAB -->
                        <div id="manual-tab-flood" class="space-y-6">
                            <div class="bg-blue-900/10 rounded-2xl p-5 border border-blue-500/20">
                                <h4 class="text-sm font-black text-blue-400 uppercase tracking-wider mb-4 flex items-center">
                                    <span>🌊</span> <span class="ml-2">In Case of Rapid Flooding:</span>
                                </h4>
                                <ul class="text-sm text-slate-300 space-y-4 list-none pl-1 font-medium leading-relaxed">
                                    <li class="flex items-start"><span class="text-blue-500 mr-3 text-lg">⚡</span><span><b class="text-blue-300 block mb-0.5">Kill Power & Gas:</b> Instantly shut down your main circuit breakers and gas valves to prevent electrocution and fires.</span></li>
                                    <li class="flex items-start"><span class="text-blue-500 mr-3 text-lg">🏔️</span><span><b class="text-blue-300 block mb-0.5">Seek High Ground:</b> Move to the highest level or rooftop immediately. Avoid attics unless there is an escape hatch.</span></li>
                                    <li class="flex items-start"><span class="text-blue-500 mr-3 text-lg">🚫</span><span><b class="text-blue-300 block mb-0.5">Avoid Moving Water:</b> Never drive or walk through floodwaters. Just 6 inches of rushing water can sweep you away.</span></li>
                                    <li class="flex items-start"><span class="text-blue-500 mr-3 text-lg">🔦</span><span><b class="text-blue-300 block mb-0.5">Prepare Signals:</b> Turn your phone flashlight on. Tie a brightly colored towel to your roof to guide rescue boats.</span></li>
                                </ul>
                            </div>
                        </div>

                        <!-- FIRE TAB -->
                        <div id="manual-tab-fire" class="hidden space-y-6">
                            <div class="bg-red-900/10 rounded-2xl p-5 border border-red-500/20">
                                <h4 class="text-sm font-black text-red-400 uppercase tracking-wider mb-4 flex items-center">
                                    <span>🔥</span> <span class="ml-2">In Case of Building Fire:</span>
                                </h4>
                                <ul class="text-sm text-slate-300 space-y-4 list-none pl-1 font-medium leading-relaxed">
                                    <li class="flex items-start"><span class="text-red-500 mr-3 text-lg">💨</span><span><b class="text-red-300 block mb-0.5">Stay Low to Ground:</b> Crawl on hands and knees under smoke where air is cleaner. Smoke inhalation is the leading danger.</span></li>
                                    <li class="flex items-start"><span class="text-red-500 mr-3 text-lg">🚪</span><span><b class="text-red-300 block mb-0.5">Check Doors First:</b> Feel doors with the back of your hand. If hot, do NOT open; seek another route.</span></li>
                                    <li class="flex items-start"><span class="text-red-500 mr-3 text-lg">🔄</span><span><b class="text-red-300 block mb-0.5">Stop, Drop, & Roll:</b> If your clothes catch fire, immediately drop to the floor and roll back and forth.</span></li>
                                    <li class="flex items-start"><span class="text-red-500 mr-3 text-lg">🔲</span><span><b class="text-red-300 block mb-0.5">Signal from Window:</b> If trapped, close doors, seal cracks with wet towels, and wave a cloth out the window.</span></li>
                                </ul>
                            </div>
                        </div>

                        <!-- MEDICAL TAB -->
                        <div id="manual-tab-cpr" class="hidden space-y-6">
                            <div class="bg-emerald-900/10 rounded-2xl p-5 border border-emerald-500/20">
                                <h4 class="text-sm font-black text-emerald-400 uppercase tracking-wider mb-4 flex items-center">
                                    <span>🩺</span> <span class="ml-2">Emergency CPR & First Aid:</span>
                                </h4>
                                <ul class="text-sm text-slate-300 space-y-4 list-none pl-1 font-medium leading-relaxed">
                                    <li class="flex items-start"><span class="text-emerald-500 mr-3 text-lg">👁️</span><span><b class="text-emerald-300 block mb-0.5">Check Responsiveness:</b> Tap shoulders and shout. Call out loudly for a first responder.</span></li>
                                    <li class="flex items-start"><span class="text-emerald-500 mr-3 text-lg">💓</span><span><b class="text-emerald-300 block mb-0.5">Chest Compressions:</b> Place two hands interlaced in the center of the chest. Push hard and fast (100-120 beats per minute).</span></li>
                                    <li class="flex items-start"><span class="text-emerald-500 mr-3 text-lg">🩹</span><span><b class="text-emerald-300 block mb-0.5">Stop Heavy Bleeding:</b> Apply direct pressure onto the wound using a clean cloth. Elevate the bleeding limb.</span></li>
                                    <li class="flex items-start"><span class="text-emerald-500 mr-3 text-lg">🛌</span><span><b class="text-emerald-300 block mb-0.5">Airway & Recovery:</b> If breathing but unconscious, roll them onto their side into the recovery position to prevent choking.</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Column (Span 1) -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Emergency Hotlines widget -->
                <div class="bg-slate-900/60 md:backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 p-6 space-y-6">
                    <h3 class="text-base font-black text-white uppercase tracking-wider flex items-center">
                        📞 Crisis Hotlines
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-slate-950/40 border border-slate-800 p-4 rounded-xl flex items-center justify-between animate-hover transition duration-200">
                            <div>
                                <span class="text-[9px] text-slate-500 uppercase tracking-widest font-bold">Civil Defense (BOMBA)</span>
                                <p class="text-base font-black text-red-400 mt-0.5">994</p>
                            </div>
                            <span class="text-lg">🔥</span>
                        </div>
                        <div class="bg-slate-950/40 border border-slate-800 p-4 rounded-xl flex items-center justify-between animate-hover transition duration-200">
                            <div>
                                <span class="text-[9px] text-slate-500 uppercase tracking-widest font-bold">Medical Emergency Services</span>
                                <p class="text-base font-black text-emerald-400 mt-0.5">999</p>
                            </div>
                            <span class="text-lg">🩺</span>
                        </div>
                        <div class="bg-slate-950/40 border border-slate-800 p-4 rounded-xl flex items-center justify-between animate-hover transition duration-200">
                            <div>
                                <span class="text-[9px] text-slate-500 uppercase tracking-widest font-bold">Royal Police (PDRM)</span>
                                <p class="text-base font-black text-blue-400 mt-0.5">999</p>
                            </div>
                            <span class="text-lg">🚓</span>
                        </div>
                    </div>
                </div>

                <!-- Offline Cache Widget -->
                <div class="bg-slate-900/60 md:backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 p-6 space-y-4">
                    <h4 class="text-xs font-black text-amber-500 uppercase tracking-widest flex items-center">
                        💾 Offline Local Storage
                    </h4>
                    <p class="text-[11px] text-slate-400 leading-relaxed font-medium">
                        All survival instructions, maps, and profile parameters are persistently cached locally in your browser storage. You can access this manual even during severe network telemetry blackouts or grid power failures.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <script>
        // Simple geolocation simulation for the purpose of the project
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);
                document.getElementById('location').value = lat + ', ' + lng;
                document.getElementById('coordinates-text').innerText = lat + ', ' + lng;
            }, function() {
                document.getElementById('location').value = "Location access denied";
                document.getElementById('coordinates-text').innerText = "Access Denied";
            });
        } else {
            document.getElementById('location').value = "Geolocation not supported";
            document.getElementById('coordinates-text').innerText = "Not Supported";
        }

        // JavaScript for profile widget tab switching
        function switchProfileTab(tab) {
            const viewTab = document.getElementById('profile-tab-view');
            const editTab = document.getElementById('profile-tab-edit');
            const viewBtn = document.getElementById('tab-btn-view');
            const editBtn = document.getElementById('tab-btn-edit');

            if (tab === 'view') {
                viewTab.classList.remove('hidden');
                editTab.classList.add('hidden');
                
                // Style Active tab
                viewBtn.className = "flex-1 text-center py-2 text-xs font-bold rounded-lg bg-slate-800 text-white border border-slate-700 shadow-sm transition duration-150";
                editBtn.className = "flex-1 text-center py-2 text-xs font-bold rounded-lg text-slate-500 hover:text-white hover:bg-slate-800/50 transition duration-150 border border-transparent";
            } else {
                viewTab.classList.add('hidden');
                editTab.classList.remove('hidden');
                
                // Style Active tab
                editBtn.className = "flex-1 text-center py-2 text-xs font-bold rounded-lg bg-slate-800 text-white border border-slate-700 shadow-sm transition duration-150";
                viewBtn.className = "flex-1 text-center py-2 text-xs font-bold rounded-lg text-slate-500 hover:text-white hover:bg-slate-800/50 transition duration-150 border border-transparent";
            }
        }

        // Emergency Chat State management
        const activeChats = {};

        function toggleIncidentChat(incidentId) {
            const chatBox = document.getElementById(`chat-box-${incidentId}`);
            const toggleText = document.getElementById(`chat-toggle-text-${incidentId}`);
            
            if (chatBox.classList.contains('hidden')) {
                chatBox.classList.remove('hidden');
                toggleText.innerText = "Close Chat Window";
                
                // Fetch immediately
                fetchChatMessages(incidentId);
                
                // Start Polling every 3 seconds
                activeChats[incidentId] = setInterval(() => fetchChatMessages(incidentId), 3000);
            } else {
                chatBox.classList.add('hidden');
                toggleText.innerText = "Open Live Emergency Chat";
                
                // Stop Polling
                clearInterval(activeChats[incidentId]);
                delete activeChats[incidentId];
            }
        }

        function fetchChatMessages(incidentId) {
            fetch(`/incidents/${incidentId}/messages`)
                .then(res => res.json())
                .then(messages => {
                    const messagesArea = document.getElementById(`chat-messages-${incidentId}`);
                    let html = '';
                    
                    if (messages.length === 0) {
                        html = '<span class="text-center text-slate-500 italic py-4 block">No messages yet. Send a message to start comms.</span>';
                    } else {
                        const currentUserId = {{ auth()->id() }};
                        messages.forEach(msg => {
                            const isMe = msg.sender_id === currentUserId;
                            const bubbleClass = isMe 
                                ? 'bg-blue-600 text-white self-end rounded-l-xl rounded-tr-xl' 
                                : 'bg-slate-700 text-slate-200 self-start rounded-r-xl rounded-tl-xl';
                            
                            html += `
                                <div class="flex flex-col max-w-[85%] ${isMe ? 'self-end' : 'self-start'}">
                                    <span class="text-[9px] font-bold text-slate-500 mb-0.5 ${isMe ? 'text-right' : 'text-left'}">${msg.sender_name} (${msg.sender_role})</span>
                                    <div class="p-2.5 px-3.5 ${bubbleClass} shadow-sm break-words leading-relaxed">
                                        <p class="m-0">${msg.message}</p>
                                    </div>
                                    <span class="text-[8px] text-slate-500 mt-1 ${isMe ? 'text-right' : 'text-left'}">${msg.time}</span>
                                </div>
                            `;
                        });
                    }
                    
                    // Determine if user was scrolled near bottom before updating
                    const wasScrolled = messagesArea.scrollHeight - messagesArea.clientHeight <= messagesArea.scrollTop + 50;
                    
                    messagesArea.innerHTML = html;
                    
                    if (wasScrolled || messagesArea.innerHTML.includes('Loading messages...')) {
                        messagesArea.scrollTop = messagesArea.scrollHeight;
                    }
                });
        }

        function sendChatMessage(event, incidentId) {
            event.preventDefault();
            const input = document.getElementById(`chat-input-${incidentId}`);
            const message = input.value.trim();
            if (!message) return;

            input.value = '';

            fetch(`/incidents/${incidentId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchChatMessages(incidentId);
                }
            });
        }

        // Offline Manual Interactions
        function toggleOfflineManual() {
            const body = document.getElementById('manual-body');
            const chevron = document.getElementById('manual-chevron');
            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                body.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        function switchManualTab(tab) {
            const tabs = ['flood', 'fire', 'cpr'];
            tabs.forEach(t => {
                const element = document.getElementById(`manual-tab-${t}`);
                const btn = document.getElementById(`tab-btn-${t}`);
                if (!element || !btn) return;
                if (t === tab) {
                    element.classList.remove('hidden');
                    btn.className = "flex-1 text-center py-3 text-xs font-black uppercase tracking-wider rounded-xl bg-slate-850 text-white border border-slate-700 shadow-sm transition duration-150";
                } else {
                    element.classList.add('hidden');
                    btn.className = "flex-1 text-center py-3 text-xs font-black uppercase tracking-wider rounded-xl text-slate-500 hover:text-white hover:bg-slate-850/50 transition duration-150 border border-transparent";
                }
            });
        }

        // Real-Time GPS Tracking Map for Citizen
        const trackingMaps = {};
        const trackingMarkers = {};

        document.addEventListener('DOMContentLoaded', () => {
            const trackingIncidents = [];
            
            @foreach($activeIncidents as $incident)
                @if($incident->status === 'En Route' || $incident->status === 'On Scene')
                    @php
                        $assignedLog = $incident->responseLogs->last();
                    @endphp
                    @if($assignedLog && $assignedLog->responder)
                        trackingIncidents.push({
                            id: {{ $incident->id }},
                            citizenCoords: '{{ $incident->location }}',
                            responderName: '{{ $assignedLog->responder->name }}'
                        });
                    @endif
                @endif
            @endforeach

            trackingIncidents.forEach(inc => {
                initializeCitizenTrackingMap(inc.id, inc.citizenCoords, inc.responderName);
            });
        });

        function initializeCitizenTrackingMap(incidentId, citizenCoords, responderName) {
            try {
                const citizenParts = citizenCoords.split(',');
                const citizenLat = parseFloat(citizenParts[0].trim());
                const citizenLng = parseFloat(citizenParts[1].trim());

                if (isNaN(citizenLat) || isNaN(citizenLng)) return;

                const mapElement = document.getElementById(`citizen-tracking-map-${incidentId}`);
                if (!mapElement) return;

                // Initialize Leaflet Map
                const map = L.map(`citizen-tracking-map-${incidentId}`, {
                    center: [citizenLat, citizenLng],
                    zoom: 15,
                    zoomControl: true
                });

                // Add Tile Layer
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap & CARTO'
                }).addTo(map);

                // Add Citizen Marker (Red)
                const citizenMarker = L.marker([citizenLat, citizenLng]).addTo(map)
                    .bindPopup('<b class="text-xs text-red-600">Your Distress Location</b>')
                    .openPopup();

                // Custom Blue Pulse Indicator for Responder
                const responderIcon = L.divIcon({
                    className: 'relative flex items-center justify-center',
                    html: `
                        <div class="h-6 w-6 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-white text-[10px] font-bold shadow-lg shadow-blue-500/50 animate-pulse">
                            🚒
                        </div>
                    `,
                    iconSize: [24, 24]
                });

                // Initialize Responder Marker slightly southwest of victim (fallback until first fetch)
                const responderMarker = L.marker([citizenLat - 0.006, citizenLng - 0.006], { icon: responderIcon }).addTo(map)
                    .bindPopup(`<b class="text-xs text-blue-600">${responderName} (En Route)</b>`);

                trackingMaps[incidentId] = map;
                trackingMarkers[incidentId] = {
                    responder: responderMarker,
                    citizen: citizenMarker,
                    poller: null
                };

                // Trigger map fit bounds
                const bounds = L.latLngBounds([
                    [citizenLat, citizenLng],
                    [citizenLat - 0.006, citizenLng - 0.006]
                ]);
                map.fitBounds(bounds, { padding: [40, 40] });

                // Start polling location
                pollResponderLocation(incidentId, responderName);
                trackingMarkers[incidentId].poller = setInterval(() => pollResponderLocation(incidentId, responderName), 3000);

                setTimeout(() => {
                    map.invalidateSize();
                }, 300);

            } catch (err) {
                console.error("Tracking map init failed:", err);
            }
        }

        function pollResponderLocation(incidentId, responderName) {
            fetch(`/incidents/${incidentId}/responder-location`)
                .then(res => res.json())
                .then(data => {
                    const map = trackingMaps[incidentId];
                    const markers = trackingMarkers[incidentId];
                    if (!map || !markers) return;

                    // If status is resolved, stop polling and reload page
                    if (data.status === 'Resolved') {
                        clearInterval(markers.poller);
                        location.reload();
                        return;
                    }

                    if (data.responder_location) {
                        try {
                            const parts = data.responder_location.split(',');
                            const respLat = parseFloat(parts[0].trim());
                            const respLng = parseFloat(parts[1].trim());

                            if (!isNaN(respLat) && !isNaN(respLng)) {
                                // Update position
                                markers.responder.setLatLng([respLat, respLng]);

                                // Update popup text based on status
                                if (data.status === 'On Scene') {
                                    markers.responder.setPopupContent(`<b class="text-xs text-emerald-600">${responderName} has Arrived!</b>`);
                                } else {
                                    markers.responder.setPopupContent(`<b class="text-xs text-blue-600">${responderName} is En Route</b>`);
                                }

                                // Smoothly zoom and fit bounds of both citizen and moving responder!
                                const cLatLng = markers.citizen.getLatLng();
                                const bounds = L.latLngBounds([cLatLng, [respLat, respLng]]);
                                map.fitBounds(bounds, { padding: [40, 40] });
                            }
                        } catch (err) {
                            console.error("Poller coordinate error:", err);
                        }
                    }
                });
        }

        // --- NEW: Live Active Incidents Polling ---
        setInterval(() => {
            fetch('{{ route("citizen.active.incidents") }}')
                .then(res => res.text())
                .then(html => {
                    const container = document.getElementById('active-incidents-container');
                    if(container) {
                        // Only replace if the HTML has changed to avoid unnecessary re-renders of the map
                        if(container.innerHTML !== html) {
                            container.innerHTML = html;
                        }
                    }
                })
                .catch(err => console.error('Failed to poll active incidents:', err));
        }, 5000); // Poll every 5 seconds
    </script>
</x-app-layout>
