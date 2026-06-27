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

        <div class="bg-slate-900/60 md:backdrop-blur-md border border-slate-800 rounded-3xl shadow-2xl overflow-hidden">
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
                    <div id="active-incidents-container">
                        @include("responder.partials.active-incidents")
                    </div>
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

        // --- NEW: Live Active Incidents Polling for Responder ---
        setInterval(() => {
            fetch('{{ route("responder.active.incidents") }}')
                .then(res => res.text())
                .then(html => {
                    const container = document.getElementById('active-incidents-container');
                    if(container) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        const newDataEl = tempDiv.querySelector('#responder-incidents-data');
                        const currentDataEl = document.getElementById('responder-incidents-data');
                        
                        let shouldUpdate = false;
                        
                        if (newDataEl && currentDataEl) {
                            if (newDataEl.getAttribute('data-state') !== currentDataEl.getAttribute('data-state')) {
                                shouldUpdate = true;
                            }
                        } else if (newDataEl || currentDataEl) {
                            shouldUpdate = true;
                        }
                        
                        if(shouldUpdate) {
                            // Find currently open chats so we can reopen them after replacing HTML
                            const openChats = Object.keys(activeChats);
                            const openChatData = {};

                            openChats.forEach(id => {
                                // Save coords from old button
                                const oldBtn = document.querySelector(`button[onclick^="toggleResponderChat(${id},"]`);
                                if (oldBtn) {
                                    const match = oldBtn.getAttribute('onclick').match(/'([^']+)'/);
                                    if (match) {
                                        openChatData[id] = match[1];
                                    }
                                }
                            });

                            // Replace HTML
                            container.innerHTML = html;
                            
                            // Reopen chats for incidents that are still active
                            openChats.forEach(id => {
                                if (openChatData[id]) {
                                    const newBtn = document.querySelector(`button[onclick^="toggleResponderChat(${id},"]`);
                                    if (newBtn) {
                                        // The old interval was cleared when toggle was called, or it wasn't.
                                        // Actually, if we just replace HTML, the old intervals in activeChats are still running!
                                        // Wait, we need to clear ALL intervals before replacing HTML?
                                        // Or just call toggleResponderChat to reopen?
                                        // If we don't clear, we'll have duplicate intervals.
                                        // Let's clear them first.
                                        clearInterval(activeChats[id]);
                                        delete activeChats[id];
                                        
                                        // Destroy old maps
                                        if (activeMaps[id]) {
                                            activeMaps[id].remove();
                                            delete activeMaps[id];
                                        }
                                        if (activeMaps[`mobile-${id}`]) {
                                            activeMaps[`mobile-${id}`].remove();
                                            delete activeMaps[`mobile-${id}`];
                                        }

                                        // Reopen chat (this will set up new maps and intervals)
                                        toggleResponderChat(id, openChatData[id]);
                                    } else {
                                        // Incident was removed, clear its interval
                                        clearInterval(activeChats[id]);
                                        delete activeChats[id];
                                    }
                                }
                            });
                        }
                    }
                })
                .catch(err => console.error('Failed to poll responder active incidents:', err));
        }, 5000);
    </script>
</x-app-layout>
