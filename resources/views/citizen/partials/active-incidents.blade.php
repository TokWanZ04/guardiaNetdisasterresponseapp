                        @if($activeIncidents->isEmpty())
                            <div class="text-center py-6">
                                <div class="bg-slate-800 h-12 w-12 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-500">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">No Active SOS Distresses</p>
                                <p class="text-[11px] text-slate-500 mt-1">If you trigger an SOS, dispatch progress will display here.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($activeIncidents as $incident)
                                    <div class="border border-slate-700 bg-slate-800/40 rounded-2xl p-5 hover:bg-slate-800/60 transition duration-150">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-extrabold text-white">
                                                        @if($incident->type === 'Medical') 🩺 Medical Emergency @elseif($incident->type === 'Fire') 🔥 Fire Incident @else 🌊 Flood Rescue @endif
                                                    </span>
                                                    <span class="text-[10px] font-bold text-slate-500 font-mono bg-slate-900 px-2 py-0.5 rounded-full">#{{ $incident->id }}</span>
                                                </div>
                                                <p class="text-xs text-slate-400 mt-2 flex items-center">
                                                    <svg class="h-3.5 w-3.5 mr-1.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Location: {{ $incident->location }}
                                                </p>
                                                <p class="text-[10px] text-slate-500 mt-1">Triggered {{ $incident->created_at->diffForHumans() }}</p>
                                            </div>

                                            <!-- Status Badge & Responder Info -->
                                            <div class="flex flex-col items-start sm:items-end justify-center">
                                                @if($incident->status === 'Pending')
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-900/30 text-amber-400 border border-amber-500/30">
                                                        <span class="relative flex h-2 w-2 mr-1.5">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                        </span>
                                                        Awaiting Dispatcher
                                                    </span>
                                                @elseif($incident->status === 'En Route')
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-900/30 text-blue-400 border border-blue-500/30 animate-pulse">
                                                        <span class="relative flex h-2 w-2 mr-1.5">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                        </span>
                                                        🚒 Responder En Route
                                                    </span>
                                                @elseif($incident->status === 'On Scene')
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-900/30 text-emerald-400 border border-emerald-500/30">
                                                        &#128652; Responders On Scene
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-slate-800 text-slate-400 border border-slate-700">
                                                        Resolved
                                                    </span>
                                                @endif

                                                <!-- Assigned Responder Name -->
                                                @php
                                                    $assignedLog = $incident->responseLogs->last();
                                                @endphp
                                                @if($assignedLog && $assignedLog->responder)
                                                    <span class="text-[11px] font-bold text-slate-300 mt-2 flex items-center bg-slate-900/80 px-2.5 py-1 rounded-lg border border-slate-700 shadow-sm">
                                                        👤 Officer: {{ $assignedLog->responder->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Progress Visual Bar for Active Incidents -->
                                        @if($incident->status !== 'Resolved')
                                            <div class="mt-4 pt-4 border-t border-slate-700/50">
                                                <div class="relative">
                                                    <div class="overflow-hidden h-1.5 text-xs flex rounded-full bg-slate-900 border border-slate-800">
                                                        <div style="width: @if($incident->status === 'Pending') 33% @elseif($incident->status === 'En Route') 66% @else 100% @endif" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center @if($incident->status === 'Pending') bg-gradient-to-r from-amber-500 to-amber-400 @elseif($incident->status === 'En Route') bg-gradient-to-r from-blue-600 to-blue-400 @else bg-gradient-to-r from-emerald-600 to-emerald-400 @endif transition-all duration-500"></div>
                                                    </div>
                                                    <div class="flex justify-between text-[9px] text-slate-500 font-bold uppercase mt-2 px-1">
                                                        <span class="@if($incident->status === 'Pending' || $incident->status === 'En Route' || $incident->status === 'On Scene') text-amber-400 @endif">1. Signal Received</span>
                                                        <span class="@if($incident->status === 'En Route' || $incident->status === 'On Scene') text-blue-400 @endif">2. En Route</span>
                                                        <span class="@if($incident->status === 'On Scene') text-emerald-400 @endif">3. Arrived</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Real-Time Dispatch Tracking Map -->
                                            @if(($incident->status === 'En Route' || $incident->status === 'On Scene') && $assignedLog && $assignedLog->responder)
                                                <div class="mt-5 pt-5 border-t border-slate-700/50">
                                                    <span class="text-[10px] font-extrabold text-blue-400 uppercase tracking-wider block mb-3 flex items-center">
                                                        <span class="relative flex h-2 w-2 mr-2">
                                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                                        </span>
                                                        📡 LIVE RESPONDER TRACKING MAP
                                                    </span>
                                                    <div id="citizen-tracking-map-{{ $incident->id }}" class="h-56 w-full rounded-2xl border border-slate-700 shadow-inner z-0" style="filter: invert(90%) hue-rotate(180deg) brightness(95%) contrast(100%);"></div>
                                                    <span class="text-[9px] text-slate-500 block mt-2 font-semibold italic text-center">Tracking dispatch vehicle location in real-time...</span>
                                                </div>
                                            @endif

                                            <!-- Collapsible Chat Box -->
                                            @if($assignedLog && $assignedLog->responder)
                                                <div class="mt-4 pt-4 border-t border-slate-700/50">
                                                    <button type="button" onclick="toggleIncidentChat({{ $incident->id }})" class="inline-flex items-center text-xs font-bold text-blue-400 hover:text-blue-300 transition bg-blue-900/20 px-3 py-1.5 rounded-lg border border-blue-900/50">
                                                        <svg class="h-4 w-4 mr-1.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                        </svg>
                                                        <span id="chat-toggle-text-{{ $incident->id }}">Open Live Emergency Chat</span>
                                                    </button>
                                                    
                                                    <div id="chat-box-{{ $incident->id }}" class="hidden mt-3 border border-slate-700 rounded-xl bg-slate-900/80 overflow-hidden shadow-sm md:backdrop-blur-md">
                                                        <div class="p-3 bg-slate-800 border-b border-slate-700 text-white flex justify-between items-center">
                                                            <span class="text-[10px] font-extrabold tracking-wide uppercase flex items-center text-slate-300">
                                                                <span class="relative flex h-2 w-2 mr-2">
                                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                                </span>
                                                                Emergency Comms Channel
                                                            </span>
                                                        </div>
                                                        
                                                        <div id="chat-messages-{{ $incident->id }}" class="p-3 max-h-48 overflow-y-auto space-y-3 flex flex-col text-xs bg-slate-900/50">
                                                            <span class="text-center text-slate-500 italic py-4 block">Loading messages...</span>
                                                        </div>

                                                        <form onsubmit="sendChatMessage(event, {{ $incident->id }})" class="p-2 border-t border-slate-700 bg-slate-800/80 flex items-center space-x-2">
                                                            <input type="text" id="chat-input-{{ $incident->id }}" placeholder="Message officer... Type safe update..." required class="flex-1 text-xs bg-slate-900 text-white border-slate-700 rounded-lg focus:ring-blue-500 focus:border-blue-500 py-2 px-3 placeholder-slate-500">
                                                            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg text-xs transition shadow-lg shadow-blue-500/20">Send</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                        @endif
                    </div>
                </div>

                <div id="tracking-incidents-data" class="hidden" data-incidents='@json($activeIncidents->filter(function($inc) { return ($inc->status === "En Route" || $inc->status === "On Scene") && $inc->responseLogs->last() && $inc->responseLogs->last()->responder; })->map(function($inc) { return ["id" => $inc->id, "citizenCoords" => $inc->location, "responderName" => $inc->responseLogs->last()->responder->name]; })->values())'></div>
