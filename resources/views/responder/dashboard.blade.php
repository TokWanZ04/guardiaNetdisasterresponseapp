<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight flex items-center justify-between">
            <span class="flex items-center space-x-2">
                <span class="text-indigo-400">🛡️</span>
                <span>{{ __('Responder Operations Console') }}</span>
            </span>
            <span class="text-xs font-black uppercase text-emerald-400 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-500/30 animate-pulse">
                🟢 Live Dispatch Mode Active
            </span>
        </h2>
        <!-- Leaflet.js Map CDNs -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    </x-slot>

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

        <div class="bg-slate-900/60 backdrop-blur-md border border-slate-800 rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-8 text-slate-100">
                <div class="flex items-center justify-between pb-6 border-b border-slate-850">
                    <div>
                        <h3 class="text-xl font-black text-white tracking-tight">Active Emergencies</h3>
                        <p class="text-slate-400 text-xs mt-1">Claim distress signals, view live status, and coordinate rescue channels.</p>
                    </div>
                    <div class="flex items-center space-x-2 bg-slate-950/80 px-3 py-1.5 rounded-xl border border-slate-850">
                        <span class="h-2 w-2 rounded-full bg-indigo-500 animate-ping"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Listening to GPS Grid...</span>
                    </div>
                </div>

                <div class="mt-6">
                    @if($incidents->isEmpty())
                        <div class="text-center py-12">
                            <div class="bg-slate-850 h-16 w-16 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-800 text-slate-500">
                                🔔
                            </div>
                            <p class="text-sm font-bold text-slate-400">All Grids Clear</p>
                            <p class="text-xs text-slate-500 mt-1">No active incidents at the moment. Standby for broadcasts.</p>
                        </div>
                    @else
                        <div class="hidden lg:block overflow-x-auto rounded-2xl border border-slate-850 bg-slate-950/40">
                            <table class="min-w-full divide-y divide-slate-800">
                                <thead class="bg-slate-900/80">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">ID</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Victim</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Type</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Location</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-850/60">
                                    @foreach($incidents as $incident)
                                        <tr class="hover:bg-slate-900/30 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-400 font-mono">#{{ $incident->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                                        <span class="font-extrabold text-sm text-white">{{ $incident->user->name }}</span>
                                                        @if($incident->user->is_pwd)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-indigo-900/50 text-indigo-300 border border-indigo-500/30">♿ PWD</span>
                                                        @endif
                                                        @if($incident->user->blood_type)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-red-900/50 text-red-300 border border-red-500/30">🩸 {{ $incident->user->blood_type }}</span>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-slate-400 mt-1 font-semibold">{{ $incident->user->phone }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs font-extrabold rounded-full border
                                                    {{ $incident->type === 'Medical' ? 'bg-blue-900/30 text-blue-300 border-blue-500/30' : '' }}
                                                    {{ $incident->type === 'Fire' ? 'bg-red-900/30 text-red-300 border-red-500/30' : '' }}
                                                    {{ $incident->type === 'Flood' ? 'bg-cyan-900/30 text-cyan-300 border-cyan-500/30' : '' }}
                                                ">
                                                    {{ $incident->type }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-slate-300 font-mono">{{ $incident->location }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($incident->status === 'Pending')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-900/30 text-amber-400 border border-amber-500/30">
                                                        Awaiting Action
                                                    </span>
                                                @elseif($incident->status === 'En Route')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-900/30 text-blue-400 border border-blue-500/30 animate-pulse">
                                                        En Route
                                                    </span>
                                                @elseif($incident->status === 'On Scene')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-900/30 text-emerald-400 border border-emerald-500/30">
                                                        On Scene
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                                        Resolved
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2.5">
                                                    <form action="{{ route('responder.status', $incident->id) }}" method="POST" class="flex space-x-2">
                                                        @csrf
                                                        @if($incident->status === 'Pending')
                                                            <input type="hidden" name="status" value="En Route">
                                                            <button type="submit" class="text-indigo-300 hover:text-white bg-indigo-900/40 px-3.5 py-1.5 rounded-xl border border-indigo-500/30 hover:border-indigo-400/50 text-xs font-extrabold transition">Claim (En Route)</button>
                                                        @elseif($incident->status === 'En Route')
                                                            <input type="hidden" name="status" value="On Scene">
                                                            <button type="submit" class="text-amber-300 hover:text-white bg-amber-900/40 px-3.5 py-1.5 rounded-xl border border-amber-500/30 hover:border-amber-400/50 text-xs font-extrabold transition">Arrived (On Scene)</button>
                                                        @elseif($incident->status === 'On Scene')
                                                            <input type="hidden" name="status" value="Resolved">
                                                            <button type="submit" class="text-emerald-300 hover:text-white bg-emerald-900/40 px-3.5 py-1.5 rounded-xl border border-emerald-500/30 hover:border-emerald-400/50 text-xs font-extrabold transition">Mark Resolved</button>
                                                        @endif
                                                    </form>

                                                    @if($incident->status !== 'Pending')
                                                        <button type="button" onclick="toggleResponderChat({{ $incident->id }}, '{{ $incident->location }}')" class="text-blue-300 hover:text-white bg-blue-900/40 px-3.5 py-1.5 rounded-xl border border-blue-500/30 hover:border-blue-400/50 text-xs font-extrabold transition flex items-center">
                                                            <svg class="h-4 w-4 mr-1 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                            </svg>
                                                            Rescue Drawer
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Expandable Chat & Details Row -->
                                        @if($incident->status !== 'Pending')
                                            <tr id="chat-row-{{ $incident->id }}" class="hidden bg-slate-950/60">
                                                <td colspan="6" class="px-6 py-6 border-b border-slate-800">
                                                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 bg-slate-900/40 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl p-6">
                                                        
                                                        <!-- Left Panel: Citizen Emergency Medical Profile -->
                                                        <div class="lg:col-span-2 border-r-0 lg:border-r border-b lg:border-b-0 border-slate-800 pr-0 lg:pr-6 pb-6 lg:pb-0 space-y-4 text-left">
                                                            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                                                <h4 class="text-xs font-black text-slate-200 flex items-center uppercase tracking-widest">
                                                                    <svg class="h-4 w-4 mr-2 text-red-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                    Citizen Medical Record
                                                                </h4>
                                                                <span class="text-[9px] font-black text-slate-500 font-mono bg-slate-950 px-2.5 py-0.5 rounded-full border border-slate-800">ID: #{{ $incident->user->id }}</span>
                                                            </div>

                                                            <!-- Contact Info -->
                                                            <div class="text-xs space-y-1.5 bg-slate-950/60 p-3 rounded-2xl border border-slate-850">
                                                                <p class="text-slate-400 font-bold">Contact Name: <span class="text-white font-extrabold">{{ $incident->user->name }}</span></p>
                                                                <p class="text-slate-400 font-bold">Phone Contact: <span class="text-white font-extrabold">{{ $incident->user->phone ?? 'Not provided' }}</span></p>
                                                            </div>

                                                            <!-- Core Metrics (Blood Type, PWD status, height, weight) -->
                                                            <div class="grid grid-cols-2 gap-3">
                                                                <div class="bg-red-900/20 p-3 rounded-2xl border border-red-500/20 text-center">
                                                                    <span class="text-[9px] font-black text-red-400 uppercase tracking-widest block">Blood Type</span>
                                                                    <span class="text-base font-black text-red-500 block mt-1">
                                                                        {{ $incident->user->blood_type ?? 'Not Set' }}
                                                                    </span>
                                                                </div>

                                                                <div class="@if($incident->user->is_pwd) bg-indigo-900/20 border-indigo-500/20 @else bg-slate-950 border-slate-850 @endif p-3 rounded-2xl border text-center">
                                                                    <span class="text-[9px] font-black @if($incident->user->is_pwd) text-indigo-400 @else text-slate-500 @endif uppercase tracking-widest block">PWD Status</span>
                                                                    <span class="text-[10px] font-black @if($incident->user->is_pwd) text-indigo-300 @else text-slate-400 @endif block mt-2">
                                                                        {{ $incident->user->is_pwd ? '♿ PWD (Yes)' : 'Standard' }}
                                                                    </span>
                                                                </div>

                                                                <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-850 text-center">
                                                                    <span class="text-[8px] font-black text-slate-500 uppercase tracking-wider block">Height</span>
                                                                    <span class="text-xs font-extrabold text-white block mt-0.5">{{ $incident->user->height ? $incident->user->height . ' cm' : '—' }}</span>
                                                                </div>

                                                                <div class="bg-slate-950 p-2.5 rounded-xl border border-slate-850 text-center">
                                                                    <span class="text-[8px] font-black text-slate-500 uppercase tracking-wider block">Weight</span>
                                                                    <span class="text-xs font-extrabold text-white block mt-0.5">{{ $incident->user->weight ? $incident->user->weight . ' kg' : '—' }}</span>
                                                                </div>
                                                            </div>

                                                            <!-- Medical Conditions & Warnings -->
                                                            <div>
                                                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-2">Declared Illnesses / Warnings</span>
                                                                @if($incident->user->diseases)
                                                                    <div class="bg-amber-900/10 border border-amber-500/20 text-amber-400 rounded-2xl p-4 text-xs font-bold leading-relaxed">
                                                                        ⚠️ {{ $incident->user->diseases }}
                                                                    </div>
                                                                @else
                                                                    <div class="bg-emerald-900/10 border border-emerald-500/20 text-emerald-400 rounded-2xl p-3.5 text-xs font-bold text-center">
                                                                        No chronic illnesses declared.
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <!-- Live GPS Street Map -->
                                                            <div class="pt-2">
                                                                <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest block mb-2">📍 Incident GPS Location Map</span>
                                                                <div id="map-{{ $incident->id }}" class="h-36 w-full rounded-2xl border border-slate-800 shadow-inner mt-2 z-0" style="filter: invert(90%) hue-rotate(180deg) brightness(95%) contrast(100%);"></div>
                                                                <span class="text-[9px] text-slate-500 block mt-2 font-mono text-center">Coordinates: {{ $incident->location }}</span>
                                                            </div>
                                                        </div>

                                                        <!-- Right Panel: Emergency Live Chat -->
                                                        <div class="lg:col-span-3 flex flex-col justify-between">
                                                            <div class="p-3 bg-slate-950 text-slate-200 flex justify-between items-center rounded-t-2xl border border-slate-800 border-b-0">
                                                                <span class="text-[10px] font-black tracking-widest uppercase flex items-center">
                                                                    <span class="relative flex h-2 w-2 mr-2.5">
                                                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                                    </span>
                                                                    Comms Channel (Active)
                                                                </span>
                                                                <button type="button" onclick="toggleResponderChat({{ $incident->id }}, '{{ $incident->location }}')" class="text-slate-500 hover:text-white font-bold text-xs bg-slate-900 px-2 py-0.5 rounded border border-slate-800">Hide Drawer</button>
                                                            </div>
                                                            
                                                            <div id="chat-messages-{{ $incident->id }}" class="p-4 h-48 overflow-y-auto space-y-3 flex flex-col text-xs bg-slate-900/30 border-x border-slate-800">
                                                                <span class="text-center text-slate-500 italic py-4 block">Loading messages...</span>
                                                            </div>

                                                            <div class="p-3.5 border border-slate-800 bg-slate-950 rounded-b-2xl space-y-3">
                                                                <form onsubmit="sendResponderChatMessage(event, {{ $incident->id }})" class="flex items-center space-x-2">
                                                                    <input type="text" id="chat-input-{{ $incident->id }}" placeholder="Type emergency instructions, ETA, coordinates check..." required class="flex-1 text-xs bg-slate-900 text-white border-slate-850 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 py-2.5 px-3 placeholder-slate-500">
                                                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition flex-shrink-0 shadow-lg shadow-indigo-500/20 border-0">Send</button>
                                                                </form>
                                                                <!-- Quick Comms Templates -->
                                                                <div class="flex items-center space-x-1.5 overflow-x-auto py-1 scrollbar-none flex-wrap gap-y-1.5">
                                                                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mr-1.5">Templates:</span>
                                                                    <button type="button" onclick="useChatTemplate({{ $incident->id }}, '🚒 Dispatching: We are dispatching rescue assets to your coordinate. Secure breakers and stay on high ground!')" class="bg-slate-900 hover:bg-indigo-950/30 text-indigo-300 hover:text-white border border-slate-800 rounded-lg px-2 py-1 text-[9px] font-bold transition flex-shrink-0">🌊 Boat/Rescue Dispatch</button>
                                                                    <button type="button" onclick="useChatTemplate({{ $incident->id }}, '🩺 First-Aid Reminder: If there is bleeding, apply firm direct pressure with a clean cloth. Stay calm, we are close.')" class="bg-slate-900 hover:bg-red-950/30 text-red-300 hover:text-white border border-slate-800 rounded-lg px-2 py-1 text-[9px] font-bold transition flex-shrink-0">🩺 First Aid Guidance</button>
                                                                    <button type="button" onclick="useChatTemplate({{ $incident->id }}, '🏠 Evac Warning: Evacuate the structure immediately if safe. Responder assets are in your perimeter!')" class="bg-slate-900 hover:bg-yellow-950/30 text-yellow-300 hover:text-white border border-slate-800 rounded-lg px-2 py-1 text-[9px] font-bold transition flex-shrink-0">🔥 Evac Immediate</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards List (block lg:hidden) -->
                        <div class="block lg:hidden space-y-4 mt-4 text-left">
                            @foreach($incidents as $incident)
                                <div class="bg-slate-900/60 backdrop-blur-md overflow-hidden shadow-2xl rounded-3xl border border-slate-800 p-5 space-y-4 hover:border-indigo-500/30 transition duration-300">
                                    <!-- Top row: ID, Type, and Status -->
                                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-bold text-slate-400 font-mono">#{{ $incident->id }}</span>
                                            <span class="px-2.5 py-0.5 inline-flex text-[10px] font-extrabold rounded-full border
                                                {{ $incident->type === 'Medical' ? 'bg-blue-900/30 text-blue-300 border-blue-500/30' : '' }}
                                                {{ $incident->type === 'Fire' ? 'bg-red-900/30 text-red-300 border-red-500/30' : '' }}
                                                {{ $incident->type === 'Flood' ? 'bg-cyan-900/30 text-cyan-300 border-cyan-500/30' : '' }}
                                            ">
                                                {{ $incident->type }}
                                            </span>
                                        </div>
                                        <div>
                                            @if($incident->status === 'Pending')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-900/30 text-amber-400 border border-amber-500/30">
                                                    Awaiting Action
                                                </span>
                                            @elseif($incident->status === 'En Route')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-blue-900/30 text-blue-400 border border-blue-500/30 animate-pulse">
                                                    En Route
                                                </span>
                                            @elseif($incident->status === 'On Scene')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-900/30 text-emerald-400 border border-emerald-500/30">
                                                    On Scene
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                                    Resolved
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Middle Content: Victim Profile -->
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="font-extrabold text-white text-sm">👤 {{ $incident->user->name }}</span>
                                            <span class="text-xs text-slate-400 font-bold font-mono">{{ $incident->user->phone }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                            @if($incident->user->is_pwd)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black bg-indigo-900/50 text-indigo-300 border border-indigo-500/30">♿ PWD</span>
                                            @endif
                                            @if($incident->user->blood_type)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-black bg-red-900/50 text-red-300 border border-red-500/30">🩸 {{ $incident->user->blood_type }}</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-bold font-mono bg-slate-950 p-2.5 rounded-xl border border-slate-850 flex items-center space-x-1">
                                            <span>📍 Coordinates:</span>
                                            <span class="text-white">{{ $incident->location }}</span>
                                        </div>
                                    </div>

                                    <!-- Bottom Row: Action Trigger Buttons -->
                                    <div class="pt-3 border-t border-slate-800 flex flex-col gap-2">
                                        <form action="{{ route('responder.status', $incident->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @if($incident->status === 'Pending')
                                                <input type="hidden" name="status" value="En Route">
                                                <button type="submit" class="w-full text-center text-indigo-300 hover:text-white bg-indigo-900/40 py-2.5 rounded-xl border border-indigo-500/30 text-xs font-extrabold transition">Claim (En Route)</button>
                                            @elseif($incident->status === 'En Route')
                                                <input type="hidden" name="status" value="On Scene">
                                                <button type="submit" class="w-full text-center text-amber-300 hover:text-white bg-amber-900/40 py-2.5 rounded-xl border border-amber-500/30 text-xs font-extrabold transition">Arrived (On Scene)</button>
                                            @elseif($incident->status === 'On Scene')
                                                <input type="hidden" name="status" value="Resolved">
                                                <button type="submit" class="w-full text-center text-emerald-300 hover:text-white bg-emerald-900/40 py-2.5 rounded-xl border border-emerald-500/30 text-xs font-extrabold transition">Mark Resolved</button>
                                            @endif
                                        </form>

                                        @if($incident->status !== 'Pending')
                                            <button type="button" onclick="toggleResponderChat({{ $incident->id }}, '{{ $incident->location }}')" class="w-full text-center text-blue-300 hover:text-white bg-blue-900/40 py-2.5 rounded-xl border border-blue-500/30 text-xs font-extrabold transition flex items-center justify-center">
                                                <svg class="h-4 w-4 mr-1.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                Rescue Drawer
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Collapsible Rescue Drawer (Mobile UI) -->
                                    @if($incident->status !== 'Pending')
                                        <div id="mobile-chat-box-{{ $incident->id }}" class="hidden pt-4 border-t border-slate-800 space-y-6 text-left">
                                            
                                            <!-- Left Panel: Citizen Emergency Medical Profile -->
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                                                    <h4 class="text-[10px] font-black text-slate-200 flex items-center uppercase tracking-widest">
                                                        <span class="text-red-500 mr-2">📋</span>
                                                        Citizen Medical Record
                                                    </h4>
                                                    <span class="text-[9px] font-black text-slate-500 font-mono bg-slate-950 px-2.5 py-0.5 rounded-full border border-slate-800">ID: #{{ $incident->user->id }}</span>
                                                </div>

                                                <div class="text-[11px] space-y-1.5 bg-slate-950/60 p-3 rounded-2xl border border-slate-850">
                                                    <p class="text-slate-400 font-bold">Contact Name: <span class="text-white font-extrabold">{{ $incident->user->name }}</span></p>
                                                    <p class="text-slate-400 font-bold">Assistance Status: 
                                                        @if($incident->user->is_pwd)
                                                            <span class="text-indigo-400 font-extrabold">♿ Person with Disabilities (PWD)</span>
                                                        @else
                                                            <span class="text-slate-500 font-semibold">Standard Clearance</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-slate-400 font-bold">Primary Blood Type: <span class="text-red-500 font-black">{{ $incident->user->blood_type ?? 'NOT SPECIFIED' }}</span></p>
                                                    <p class="text-slate-400 font-bold">Height / Weight: <span class="text-slate-200 font-extrabold">{{ $incident->user->height ? $incident->user->height . ' cm' : 'N/A' }} / {{ $incident->user->weight ? $incident->user->weight . ' kg' : 'N/A' }}</span></p>
                                                </div>

                                                <div class="text-[11px] space-y-2 bg-slate-950/60 p-3 rounded-2xl border border-slate-850">
                                                    <span class="text-[10px] font-black uppercase text-red-400 tracking-wider block">🚨 Declared Chronic Illnesses</span>
                                                    <p class="text-slate-300 font-semibold leading-relaxed bg-red-950/10 p-2.5 rounded-xl border border-red-955/50">
                                                        {{ $incident->user->chronic_illness ?? 'No chronic health issues or allergies declared.' }}
                                                    </p>
                                                </div>

                                                <div class="space-y-2">
                                                    <span class="text-[10px] font-black uppercase text-blue-400 tracking-wider block">🗺️ Live GPS Tracking Beacon</span>
                                                    <div id="mobile-map-{{ $incident->id }}" class="h-44 w-full rounded-2xl border border-slate-800 shadow-inner mt-2 z-0" style="filter: invert(90%) hue-rotate(180deg) brightness(95%) contrast(100%);"></div>
                                                </div>
                                            </div>

                                            <!-- Right Panel: Emergency Live Chat -->
                                            <div class="space-y-4">
                                                <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                                                    <h4 class="text-[10px] font-black text-slate-200 flex items-center uppercase tracking-widest">
                                                        <span class="text-blue-500 mr-2">💬</span>
                                                        Emergency Live Chat
                                                    </h4>
                                                    <span class="text-[9px] font-black text-emerald-400 bg-emerald-950/50 px-2.5 py-0.5 rounded-full border border-emerald-500/20 animate-pulse">Secure Beacon Link</span>
                                                </div>

                                                <!-- Messages Area -->
                                                <div id="mobile-chat-messages-{{ $incident->id }}" class="p-3 h-48 overflow-y-auto space-y-3 flex flex-col text-xs bg-slate-950/60 rounded-2xl border border-slate-850">
                                                    <span class="text-center text-slate-500 italic py-4 block">Loading messages...</span>
                                                </div>

                                                <!-- Chat Form -->
                                                <form onsubmit="sendResponderChatMessage(event, {{ $incident->id }}, true)" class="p-2 border border-slate-800 bg-slate-950 rounded-2xl flex items-center space-x-2">
                                                    <input type="text" id="mobile-chat-input-{{ $incident->id }}" placeholder="Message officer... Type safe update..." required class="flex-1 text-[11px] bg-slate-900 text-white border-slate-800 rounded-xl focus:ring-blue-500 focus:border-blue-500 py-2 px-3 placeholder-slate-500">
                                                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-2 px-4 rounded-xl text-[10px] uppercase tracking-widest transition shadow-lg shadow-blue-500/20">Send</button>
                                                </form>

                                                <!-- Templates -->
                                                <div class="bg-slate-950/60 p-3 rounded-2xl border border-slate-850 space-y-2">
                                                    <span class="text-[9px] font-black uppercase text-slate-500 tracking-widest block">Operational Templates</span>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        <button type="button" onclick="useChatTemplate({{ $incident->id }}, '🚨 First Responder Status: Dispatch claimed. Rescuer team is mobilizing to your exact GPS coordinates. Stay calm!')" class="bg-slate-900 hover:bg-indigo-950/30 text-indigo-300 hover:text-white border border-slate-800 rounded-lg px-2 py-1 text-[9px] font-bold transition">🚨 Claimed</button>
                                                        <button type="button" onclick="useChatTemplate({{ $incident->id }}, '🚒 Dispatch Update: Rescue vehicles have arrived at your designated sector. Standby for visual contact.')" class="bg-slate-900 hover:bg-blue-950/30 text-blue-300 hover:text-white border border-slate-800 rounded-lg px-2 py-1 text-[9px] font-bold transition">🚒 Arrived</button>
                                                        <button type="button" onclick="useChatTemplate({{ $incident->id }}, '🏠 Evac Warning: Evacuate the structure immediately if safe. Responder assets are in your perimeter!')" class="bg-slate-900 hover:bg-yellow-950/30 text-yellow-300 hover:text-white border border-slate-800 rounded-lg px-2 py-1 text-[9px] font-bold transition">🔥 Evac</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        const activeChats = {};
        const activeMaps = {};

        function toggleResponderChat(incidentId, rawCoords) {
            const chatRow = document.getElementById(`chat-row-${incidentId}`);
            const mobileChatBox = document.getElementById(`mobile-chat-box-${incidentId}`);
            
            let isOpen = false;

            if (chatRow) {
                if (chatRow.classList.contains('hidden')) {
                    chatRow.classList.remove('hidden');
                    isOpen = true;
                } else {
                    chatRow.classList.add('hidden');
                }
            }

            if (mobileChatBox) {
                if (mobileChatBox.classList.contains('hidden')) {
                    mobileChatBox.classList.remove('hidden');
                    isOpen = true;
                } else {
                    mobileChatBox.classList.add('hidden');
                }
            }

            if (isOpen) {
                // Fetch immediately
                fetchResponderChat(incidentId);
                
                // Start polling every 3 seconds
                activeChats[incidentId] = setInterval(() => fetchResponderChat(incidentId), 3000);

                // Initialize GPS Map
                initializeIncidentMap(incidentId, rawCoords);
            } else {
                // Stop polling
                clearInterval(activeChats[incidentId]);
                delete activeChats[incidentId];

                // Destroy map instances to free memory
                if (activeMaps[incidentId]) {
                    activeMaps[incidentId].remove();
                    delete activeMaps[incidentId];
                }
                if (activeMaps[`mobile-${incidentId}`]) {
                    activeMaps[`mobile-${incidentId}`].remove();
                    delete activeMaps[`mobile-${incidentId}`];
                }
            }
        }

        function initializeIncidentMap(incidentId, rawCoords) {
            if (!rawCoords || rawCoords.includes('denied') || rawCoords.includes('not supported') || rawCoords.includes('Location')) {
                const mapEl = document.getElementById(`map-${incidentId}`);
                if (mapEl) mapEl.innerHTML = '<div class="h-full flex items-center justify-center text-xs text-slate-500 bg-slate-950 rounded-2xl border border-slate-850 italic">GPS Coordinates Unavailable</div>';
                const mobMapEl = document.getElementById(`mobile-map-${incidentId}`);
                if (mobMapEl) mobMapEl.innerHTML = '<div class="h-full flex items-center justify-center text-xs text-slate-500 bg-slate-950 rounded-2xl border border-slate-850 italic">GPS Coordinates Unavailable</div>';
                return;
            }

            try {
                const parts = rawCoords.split(',');
                const lat = parseFloat(parts[0].trim());
                const lng = parseFloat(parts[1].trim());

                if (isNaN(lat) || isNaN(lng)) {
                    const mapEl = document.getElementById(`map-${incidentId}`);
                    if (mapEl) mapEl.innerHTML = '<div class="h-full flex items-center justify-center text-xs text-slate-500 bg-slate-950 rounded-2xl border border-slate-850 italic">Invalid GPS Coordinates</div>';
                    const mobMapEl = document.getElementById(`mobile-map-${incidentId}`);
                    if (mobMapEl) mobMapEl.innerHTML = '<div class="h-full flex items-center justify-center text-xs text-slate-500 bg-slate-950 rounded-2xl border border-slate-850 italic">Invalid GPS Coordinates</div>';
                    return;
                }

                // Desktop map initialization
                const desktopMapContainer = document.getElementById(`map-${incidentId}`);
                if (desktopMapContainer) {
                    if (activeMaps[incidentId]) {
                        activeMaps[incidentId].remove();
                    }

                    const map = L.map(`map-${incidentId}`, {
                        center: [lat, lng],
                        zoom: 15,
                        zoomControl: true
                    });

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap & CARTO'
                    }).addTo(map);

                    L.circle([lat, lng], {
                        color: '#ef4444',
                        fillColor: '#ef4444',
                        fillOpacity: 0.3,
                        radius: 120
                    }).addTo(map);

                    L.marker([lat, lng]).addTo(map)
                        .bindPopup(`<b class="text-xs text-red-500">Incident Alert #${incidentId}</b>`)
                        .openPopup();

                    activeMaps[incidentId] = map;

                    setTimeout(() => {
                        map.invalidateSize();
                    }, 300);
                }

                // Mobile map initialization
                const mobileMapContainer = document.getElementById(`mobile-map-${incidentId}`);
                if (mobileMapContainer) {
                    if (activeMaps[`mobile-${incidentId}`]) {
                        activeMaps[`mobile-${incidentId}`].remove();
                    }

                    const mapMob = L.map(`mobile-map-${incidentId}`, {
                        center: [lat, lng],
                        zoom: 15,
                        zoomControl: true
                    });

                    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap & CARTO'
                    }).addTo(mapMob);

                    L.circle([lat, lng], {
                        color: '#ef4444',
                        fillColor: '#ef4444',
                        fillOpacity: 0.3,
                        radius: 120
                    }).addTo(mapMob);

                    L.marker([lat, lng]).addTo(mapMob)
                        .bindPopup(`<b class="text-xs text-red-500">Incident Alert #${incidentId}</b>`)
                        .openPopup();

                    activeMaps[`mobile-${incidentId}`] = mapMob;

                    setTimeout(() => {
                        mapMob.invalidateSize();
                    }, 300);
                }

            } catch (err) {
                console.error("Map error:", err);
                const mapEl = document.getElementById(`map-${incidentId}`);
                if (mapEl) mapEl.innerHTML = '<div class="h-full flex items-center justify-center text-xs text-slate-500 bg-slate-950 rounded-2xl border border-slate-850 italic">Map Loading Failed</div>';
                const mobMapEl = document.getElementById(`mobile-map-${incidentId}`);
                if (mobMapEl) mobMapEl.innerHTML = '<div class="h-full flex items-center justify-center text-xs text-slate-500 bg-slate-950 rounded-2xl border border-slate-850 italic">Map Loading Failed</div>';
            }
        }

        function fetchResponderChat(incidentId) {
            fetch(`/incidents/${incidentId}/messages`)
                .then(res => res.json())
                .then(messages => {
                    let html = '';
                    
                    if (messages.length === 0) {
                        html = '<span class="text-center text-slate-500 italic py-4 block">No messages yet. Check in with the citizen.</span>';
                    } else {
                        const currentUserId = {{ auth()->id() }};
                        messages.forEach(msg => {
                            const isMe = msg.sender_id === currentUserId;
                            const bubbleClass = isMe 
                                ? 'bg-indigo-600 text-white self-end rounded-l-xl rounded-tr-xl' 
                                : 'bg-slate-850 text-slate-200 self-start rounded-r-xl rounded-tl-xl';
                            
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
                    
                    // Desktop messages render
                    const messagesArea = document.getElementById(`chat-messages-${incidentId}`);
                    if (messagesArea) {
                        const wasScrolled = messagesArea.scrollHeight - messagesArea.clientHeight <= messagesArea.scrollTop + 50;
                        messagesArea.innerHTML = html;
                        if (wasScrolled || messagesArea.innerHTML.includes('Loading messages...')) {
                            messagesArea.scrollTop = messagesArea.scrollHeight;
                        }
                    }

                    // Mobile messages render
                    const mobileMessagesArea = document.getElementById(`mobile-chat-messages-${incidentId}`);
                    if (mobileMessagesArea) {
                        const wasScrolledMob = mobileMessagesArea.scrollHeight - mobileMessagesArea.clientHeight <= mobileMessagesArea.scrollTop + 50;
                        mobileMessagesArea.innerHTML = html;
                        if (wasScrolledMob || mobileMessagesArea.innerHTML.includes('Loading messages...')) {
                            mobileMessagesArea.scrollTop = mobileMessagesArea.scrollHeight;
                        }
                    }
                });
        }

        function sendResponderChatMessage(event, incidentId, isMobile = false) {
            event.preventDefault();
            const inputId = isMobile ? `mobile-chat-input-${incidentId}` : `chat-input-${incidentId}`;
            const input = document.getElementById(inputId);
            const message = input.value.trim();
            if (!message) return;

            submitChatMessage(incidentId, message);
            input.value = '';
        }

        function submitChatMessage(incidentId, messageText) {
            fetch(`/incidents/${incidentId}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: messageText })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fetchResponderChat(incidentId);
                }
            });
        }

        function useChatTemplate(incidentId, templateText) {
            submitChatMessage(incidentId, templateText);
        }

        // Start background location pinger for claimed active incidents (En Route, On Scene)
        document.addEventListener('DOMContentLoaded', () => {
            const activeIncidentsToTrack = [];
            
            @foreach($incidents as $incident)
                @if($incident->status === 'En Route' || $incident->status === 'On Scene')
                    activeIncidentsToTrack.push({
                        id: {{ $incident->id }},
                        status: '{{ $incident->status }}',
                        citizenCoords: '{{ $incident->location }}'
                    });
                @endif
            @endforeach

            activeIncidentsToTrack.forEach(inc => {
                startLocationStreaming(inc.id, inc.citizenCoords, inc.status);
            });
        });

        const activeStreams = {};

        function startLocationStreaming(incidentId, citizenCoords, status) {
            if (activeStreams[incidentId]) clearInterval(activeStreams[incidentId]);

            let step = 0;
            const maxSteps = 10; 
            let currentLat, currentLng;
            let targetLat, targetLng;

            try {
                const parts = citizenCoords.split(',');
                targetLat = parseFloat(parts[0].trim());
                targetLng = parseFloat(parts[1].trim());

                // Simulated start coordinates: southwest of victim
                currentLat = targetLat - 0.006;
                currentLng = targetLng - 0.006;
            } catch (err) {
                console.error("Tracking parse error:", err);
                return;
            }

            function pingLocation() {
                if (status === 'On Scene') {
                    sendCoordinatesToServer(incidentId, `${targetLat},${targetLng}`);
                    return;
                }

                // Smoothly crawl towards the target
                if (step < maxSteps) {
                    step++;
                    currentLat += (targetLat - currentLat) * (step / maxSteps);
                    currentLng += (targetLng - currentLng) * (step / maxSteps);
                } else {
                    currentLat = targetLat;
                    currentLng = targetLng;
                }

                const locStr = `${currentLat.toFixed(6)},${currentLng.toFixed(6)}`;
                sendCoordinatesToServer(incidentId, locStr);
            }

            pingLocation();
            activeStreams[incidentId] = setInterval(pingLocation, 3000);
        }

        function sendCoordinatesToServer(incidentId, locationStr) {
            fetch(`/incidents/${incidentId}/responder-location`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ location: locationStr })
            })
            .then(res => res.json())
            .then(data => {
                // Location sent successfully
            });
        }
    </script>
</x-app-layout>
